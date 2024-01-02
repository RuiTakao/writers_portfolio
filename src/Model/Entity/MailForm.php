<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MailForm Entity
 *
 * @property int $id
 * @property string|null $mail
 * @property string $mail_form_text
 * @property bool $view_mail_form
 * @property bool $view_form_tel
 * @property bool $view_form_pattern
 * @property bool $view_form_name_kana
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 */
class MailForm extends Entity
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
        'mail' => true,
        'mail_form_text' => true,
        'view_mail_form' => true,
        'view_form_tel' => true,
        'view_form_pattern' => true,
        'view_form_name_kana' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
    ];
}
