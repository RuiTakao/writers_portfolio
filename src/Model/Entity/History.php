<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * History Entity
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $overview
 * @property string|null $start
 * @property string|null $end
 * @property string|null $title2
 * @property string|null $overview2
 * @property string|null $start2
 * @property string|null $end2
 * @property string|null $title3
 * @property string|null $overview3
 * @property string|null $start3
 * @property string|null $end3
 * @property string|null $title4
 * @property string|null $overview4
 * @property string|null $start4
 * @property string|null $end4
 * @property string|null $title5
 * @property string|null $overview5
 * @property string|null $start5
 * @property string|null $end5
 * @property string|null $title6
 * @property string|null $overview6
 * @property string|null $start6
 * @property string|null $end6
 * @property string|null $title7
 * @property string|null $overview7
 * @property string|null $start7
 * @property string|null $end7
 * @property string|null $title8
 * @property string|null $overview8
 * @property string|null $start8
 * @property string|null $end8
 * @property string|null $title9
 * @property string|null $overview9
 * @property string|null $start9
 * @property string|null $end9
 * @property string|null $title10
 * @property string|null $overview10
 * @property string|null $start10
 * @property string|null $end10
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 */
class History extends Entity
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
        'overview' => true,
        'start' => true,
        'end' => true,
        'to_now' => true,
        'history_order' => true,
        'title2' => true,
        'overview2' => true,
        'start2' => true,
        'end2' => true,
        'title3' => true,
        'overview3' => true,
        'start3' => true,
        'end3' => true,
        'title4' => true,
        'overview4' => true,
        'start4' => true,
        'end4' => true,
        'title5' => true,
        'overview5' => true,
        'start5' => true,
        'end5' => true,
        'title6' => true,
        'overview6' => true,
        'start6' => true,
        'end6' => true,
        'title7' => true,
        'overview7' => true,
        'start7' => true,
        'end7' => true,
        'title8' => true,
        'overview8' => true,
        'start8' => true,
        'end8' => true,
        'title9' => true,
        'overview9' => true,
        'start9' => true,
        'end9' => true,
        'title10' => true,
        'overview10' => true,
        'start10' => true,
        'end10' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
    ];
}
