<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Other Entity
 *
 * @property int $id
 * @property string $title
 * @property string|null $content1
 * @property string|null $content2
 * @property string|null $content3
 * @property string|null $content4
 * @property string|null $content5
 * @property string|null $content6
 * @property string|null $content7
 * @property string|null $content8
 * @property string|null $content9
 * @property string|null $content10
 * @property string $order
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Other extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'title' => true,
        'content1' => true,
        'content2' => true,
        'content3' => true,
        'content4' => true,
        'content5' => true,
        'content6' => true,
        'content7' => true,
        'content8' => true,
        'content9' => true,
        'content10' => true,
        'others_order' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
    ];
}
