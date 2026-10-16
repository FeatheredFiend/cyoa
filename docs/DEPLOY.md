# Deploying

The CYOA builder deploys the same way as the games and the portfolio: every push to `main` that passes the checks is published to a `deploy` branch, and the host keeps a Git clone of that branch as the site's folder, updated by a cron job.

```
push to main ─▶ GitHub Actions: checks, build, assemble ─▶ deploy branch ─▶ cron on the host: git pull + migrations ─▶ live
```

## How it fits together

- **The workflow** is `.github/workflows/deploy.yml`. It builds the Encore assets, lints the templates, config and service container, validates the Doctrine mapping, installs production packages only, compiles `.env` to `.env.local.php` for prod, and publishes the result to `deploy`. Pull requests run the checks and stop there.
- **The layout** is Symfony's own. The site's folder is `~/cyoa.proprietary-data.com` and the subdomain's document root is its `public/`, so nothing else in the tree (code, `vendor/`, `.git`, logs) can be fetched over HTTP.
- **Secrets** never go in the repository (which is public) or the site's folder. `config/runtime.php` loads `~/cyoa.env`, the folder above the site, for `APP_SECRET` and `DATABASE_URL`.
- **Debug is off.** The compiled `.env.local.php` sets `APP_ENV=prod`, so errors show a plain page. Never put `APP_DEBUG` in `~/cyoa.env`.
- **The cache.** The workflow writes the commit into `BUILD`, and `Kernel::getCacheDir()` puts each build's cache in its own `var/cache/prod-<build>` folder. A new deploy starts a fresh cache by itself, with no cache clearing on the host. Old folders can be deleted at any time.
- **Migrations** run from the cron job after each pull, so a schema change ships like any other change.
- **Uploads** (enemy and paragraph images) are written to `public/images/enemy/` and `public/images/paragraph/` inside the clone. Git leaves untracked files alone, so pulls never touch them.
- **Logs** are in `~/cyoa.proprietary-data.com/var/log/`, one JSON file a day, kept for 14 days.

## Switching over from the uploaded zip (once)

Everything below is in cPanel's **Terminal**. Where a command runs PHP, `PHP84` stands for this host's PHP 8.4 command. The portfolio's cron uses `/opt/alt/php84/usr/bin/php`; check it's still the one:

```sh
for p in php /usr/local/bin/php /usr/bin/php /opt/alt/php84/usr/bin/php /opt/cpanel/ea-php84/root/usr/bin/php; do command -v "$p" >/dev/null && echo "$(command -v "$p") -> $("$p" -r 'echo PHP_VERSION;')"; done
```

Use the full path that prints 8.4 wherever `PHP84` appears, and especially in the cron job: cron doesn't search the same folders as Terminal.

1. **Wait for the first green run on `main`.** It creates the `deploy` branch. Until the host is switched over, the live site carries on as it is.

2. **Check the document root.** Under **Domains**, `cyoa.proprietary-data.com` should have the document root `cyoa.proprietary-data.com/public`. The steps below keep it.

3. **Check for a cPanel PHP block in the current `.htaccess`:**

   ```sh
   grep -n "cPanel-generated" ~/cyoa.proprietary-data.com/public/.htaccess || echo "none"
   ```

   If it prints `none`, carry on. If it prints a block, see [If cPanel manages the PHP version](#if-cpanel-manages-the-php-version) after step 6.

4. **Move the secrets out of the site's folder.** The zip deploy left the real values in `.env.local`:

   ```sh
   grep -E '^(APP_SECRET|DATABASE_URL)=' ~/cyoa.proprietary-data.com/.env.local > ~/cyoa.env
   chmod 600 ~/cyoa.env
   cat ~/cyoa.env
   ```

   Check it shows exactly two lines, with nothing stray at the ends. Keeping the same `APP_SECRET` keeps everyone signed in. Leave out `APP_ENV` and `APP_DEBUG`: the live site has been running with debug on, which shows visitors Symfony's full error pages and the server's file paths.

5. **Clone the `deploy` branch and bring the uploads across.** The repository is public, so no key is needed:

   ```sh
   cd ~
   git clone -q --branch deploy --single-branch https://github.com/FeatheredFiend/cyoa.git cyoa-deploy
   cp -an cyoa.proprietary-data.com/public/images/. cyoa-deploy/public/images/
   [ -d cyoa.proprietary-data.com/public/.well-known ] && cp -a cyoa.proprietary-data.com/public/.well-known cyoa-deploy/public/
   printf '/var/\n/public/images/enemy/\n/public/images/paragraph/\n' >> cyoa-deploy/.git/info/exclude
   ```

   `cp -an` copies every uploaded image and skips the ones the branch already has. The last line keeps uploads and logs out of `git status` on the host.

6. **Swap the folders.** Two renames, so the site is down for a moment at most:

   ```sh
   mv cyoa.proprietary-data.com cyoa.zip-old && mv cyoa-deploy cyoa.proprietary-data.com
   ```

   To undo it: `mv cyoa.proprietary-data.com cyoa-deploy && mv cyoa.zip-old cyoa.proprietary-data.com`.

7. **Check the database knows its migrations.** The schema was created from pasted SQL that also recorded each version:

   ```sh
   cd ~/cyoa.proprietary-data.com && PHP84 bin/console doctrine:migrations:status
   ```

   Expect **Executed 20** and **New 0**. If it lists new migrations whose tables already exist, mark the existing schema as migrated, once:

   ```sh
   PHP84 bin/console doctrine:migrations:version --add --all -n
   ```

8. **Add the cron job.** Under **Cron Jobs**, every five minutes (`*/5 * * * *`), with `PHP84` replaced by the full path:

   ```sh
   cd ~/cyoa.proprietary-data.com && git pull -q --ff-only origin deploy && PHP84 bin/console doctrine:migrations:migrate -n -q --allow-no-migration
   ```

9. **Check it.**
   - https://cyoa.proprietary-data.com shows the sign-in page, and signing in works.
   - An adventure with enemy or paragraph images still shows them.
   - `curl -sI https://cyoa.proprietary-data.com/no-such-page | grep -i x-debug` prints nothing. Before the switch it printed `X-Debug-Exception`.
   - `curl -sI https://cyoa.proprietary-data.com | grep -i x-powered-by` still says PHP 8.4.

10. **Tidy up**, once you're happy: `rm -rf ~/cyoa.zip-old`. On your own machine, `cyoa-deploy.zip`, `cyoa-deploy-build/`, `cyoa-htaccess-fix.zip` and `cyoa-migration.sql` in `~/projects` are no longer needed.

From then on, every push to `main` is live within about five minutes of its run going green.

## If something goes wrong

- **Old version still showing:** run `git -C ~/cyoa.proprietary-data.com log -1 --oneline` and compare it with the latest commit on the `deploy` branch on GitHub. If they differ, run the cron command by hand to see its error. If they match, check that `~/cyoa.proprietary-data.com/BUILD` changed, and hard-refresh the browser.
- **A 500 error:** read the end of the newest file in `~/cyoa.proprietary-data.com/var/log/`. The usual causes are a wrong line in `~/cyoa.env`, or PHP older than 8.4.
- **The pull says local changes would be overwritten:** something on the host edited a tracked file, most likely cPanel writing into `public/.htaccess`. `git -C ~/cyoa.proprietary-data.com status` names the file. See the next section.

### If cPanel manages the PHP version

cPanel's MultiPHP Manager sets a domain's PHP version by writing a block into the `.htaccess` in its document root. Here that file comes from Git, so after the swap, the block from the old folder is gone.

- If PHP still reports 8.4 after the swap (step 9), nothing is needed.
- If it doesn't, set **cyoa.proprietary-data.com** to **PHP 8.4** in **MultiPHP Manager** again, which writes the block into the new `public/.htaccess`. Then tell Git to leave that edit alone: `git -C ~/cyoa.proprietary-data.com update-index --skip-worktree public/.htaccess`. Pulls then carry on as normal. If a later deploy changes `public/.htaccess` itself, the pull stops with a message rather than breaking the site: run `git -C ~/cyoa.proprietary-data.com update-index --no-skip-worktree public/.htaccess`, then `git -C ~/cyoa.proprietary-data.com checkout public/.htaccess`, pull, and set the PHP version again.
