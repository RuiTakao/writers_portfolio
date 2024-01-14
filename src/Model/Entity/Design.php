<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Design Entity
 *
 * @property int $id
 * @property int|null $fv_design
 * @property string|null $fv_image_path
 * @property int $fv_image_positionX
 * @property int $fv_image_positionY
 * @property string|null $fv_image_sp_path
 * @property int $fv_image_sp_positionX
 * @property int $fv_image_sp_positionY
 * @property string|null $body_color
 * @property string|null $title_color
 * @property string|null $title_border_color
 * @property int $user_id
 *
 * @property \App\Model\Entity\User $user
 */
class Design extends Entity
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
        'fv_design' => true,
        'fv_image_path' => true,
        'fv_image_positionX' => true,
        'fv_image_positionY' => true,
        'fv_image_sp_path' => true,
        'fv_image_sp_positionX' => true,
        'fv_image_sp_positionY' => true,
        'body_color' => true,
        'title_color' => true,
        'title_border_color' => true,
        'user_id' => true,
        'user' => true,
    ];
}
