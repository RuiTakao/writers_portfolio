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
 * @property int $header_image_positionX
 * @property int $header_image_positionY
 * @property int $header_image_opacity
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $histories_title
 * @property bool $histories_flg
 * @property string $works_title
 * @property bool $works_flg
 * @property string $others_title
 * @property bool $others_flg
 * @property string $contacts_title
 * @property bool $contacts_flg
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
        'header_image_positionX' => true,
        'header_image_positionY' => true,
        'header_image_opacity' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'histories_title' => true,
        'histories_flg' => true,
        'works_title' => true,
        'works_flg' => true,
        'others_title' => true,
        'others_flg' => true,
        'contacts_title' => true,
        'contacts_flg' => true,
        'user' => true,
    ];
}
