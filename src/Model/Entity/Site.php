<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Site Entity
 *
 * @property int $id
 * @property string|null $site_title
 * @property string|null $site_description
 * @property string|null $favicon_path
 * @property string|null $heaher_image_path
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Site extends Entity
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
        'site_title' => true,
        'site_description' => true,
        'favicon_path' => true,
        'header_image_path' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
    ];
}
