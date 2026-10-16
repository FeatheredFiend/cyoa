<?php

namespace App\Controller;

use App\Entity\Adventure;
use App\Entity\AdventureParagraph;
use App\Entity\BattleCategory;
use App\Entity\Enemy;
use App\Entity\Equipment;
use App\Entity\EquipmentEffect;
use App\Entity\EquipmentEffectAttribute;
use App\Entity\EquipmentEffectOperator;
use App\Entity\Gamebook;
use App\Entity\GamebookPermission;
use App\Entity\Hero;
use App\Entity\HeroAttribute;
use App\Entity\HeroEquipment;
use App\Entity\HeroSpell;
use App\Entity\Magic;
use App\Entity\MagicEffect;
use App\Entity\MagicEffectAttribute;
use App\Entity\MagicEffectOperator;
use App\Entity\MagicEquipment;
use App\Entity\Merchant;
use App\Entity\MerchantInventory;
use App\Entity\Paragraph;
use App\Entity\ParagraphAction;
use App\Entity\ParagraphActionCategory;
use App\Entity\ParagraphActionEnemy;
use App\Entity\ParagraphActionEquipmentRequired;
use App\Entity\ParagraphActionOperator;
use App\Entity\ParagraphActionSpell;
use App\Entity\ParagraphActionTarget;
use App\Entity\ParagraphDirection;
use App\Entity\ParagraphDirectionEquipmentRequired;
use App\Entity\ParagraphDirectionSpell;
use App\Entity\ParagraphEquipment;
use App\Entity\ParagraphSpell;
use App\Entity\Spell;
use Doctrine\DBAL\Exception as DbalException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Single delete endpoint shared by every CRUD list table, rather than a
 * duplicated delete() action per entity controller. The entity slug is
 * checked against this allowlist so the route can never be used to touch
 * a class outside of it (User included).
 */
class DeleteController extends AbstractController
{
    private const ENTITY_MAP = [
        'adventure' => Adventure::class,
        'adventureparagraph' => AdventureParagraph::class,
        'battlecategory' => BattleCategory::class,
        'enemy' => Enemy::class,
        'equipment' => Equipment::class,
        'equipmenteffect' => EquipmentEffect::class,
        'equipmenteffectattribute' => EquipmentEffectAttribute::class,
        'equipmenteffectoperator' => EquipmentEffectOperator::class,
        'gamebook' => Gamebook::class,
        'gamebookpermission' => GamebookPermission::class,
        'hero' => Hero::class,
        'heroattribute' => HeroAttribute::class,
        'heroequipment' => HeroEquipment::class,
        'herospell' => HeroSpell::class,
        'magic' => Magic::class,
        'magiceffect' => MagicEffect::class,
        'magiceffectattribute' => MagicEffectAttribute::class,
        'magiceffectoperator' => MagicEffectOperator::class,
        'magicequipment' => MagicEquipment::class,
        'merchant' => Merchant::class,
        'merchantinventory' => MerchantInventory::class,
        'paragraph' => Paragraph::class,
        'paragraphaction' => ParagraphAction::class,
        'paragraphactioncategory' => ParagraphActionCategory::class,
        'paragraphactionenemy' => ParagraphActionEnemy::class,
        'paragraphactionequipmentrequired' => ParagraphActionEquipmentRequired::class,
        'paragraphactionoperator' => ParagraphActionOperator::class,
        'paragraphactionspell' => ParagraphActionSpell::class,
        'paragraphactiontarget' => ParagraphActionTarget::class,
        'paragraphdirection' => ParagraphDirection::class,
        'paragraphdirectionequipmentrequired' => ParagraphDirectionEquipmentRequired::class,
        'paragraphdirectionspell' => ParagraphDirectionSpell::class,
        'paragraphequipment' => ParagraphEquipment::class,
        'paragraphspell' => ParagraphSpell::class,
        'spell' => Spell::class,
    ];

    #[Route('/delete/{entity}/{id}', name: 'generic_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(string $entity, int $id, Request $request, ManagerRegistry $doctrine): RedirectResponse
    {
        $redirect = $request->request->get('redirect');
        // Only ever redirect back to a same-site path, never an
        // attacker-suppliable absolute/protocol-relative URL.
        if (!is_string($redirect) || !preg_match('#^/(?!/)#', $redirect)) {
            $redirect = $this->generateUrl('homepage');
        }

        if (!$this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if (!isset(self::ENTITY_MAP[$entity])) {
            throw $this->createNotFoundException();
        }

        if (!$this->isCsrfTokenValid('delete_' . $entity . '_' . $id, $request->request->get('_token'))) {
            $this->addFlash('failure', 'That delete link had expired — please try again.');

            return $this->redirect($redirect);
        }

        $em = $doctrine->getManager();
        $object = $em->getRepository(self::ENTITY_MAP[$entity])->find($id);

        if ($object === null) {
            $this->addFlash('failure', 'That item no longer exists.');

            return $this->redirect($redirect);
        }

        try {
            $em->remove($object);
            $em->flush();
            $this->addFlash('success', 'Deleted successfully.');
        } catch (DbalException $e) {
            $this->addFlash('failure', 'Could not delete this — other records still depend on it. Remove those first.');
        }

        return $this->redirect($redirect);
    }
}
