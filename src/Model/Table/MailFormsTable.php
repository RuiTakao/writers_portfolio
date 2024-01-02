<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MailForms Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\MailForm newEmptyEntity()
 * @method \App\Model\Entity\MailForm newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MailForm[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MailForm get($primaryKey, $options = [])
 * @method \App\Model\Entity\MailForm findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\MailForm patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MailForm[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MailForm|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MailForm saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MailForm[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MailForm[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\MailForm[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MailForm[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MailFormsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('mail_forms');
        $this->setDisplayField('mail_form_text');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('mail')
            ->maxLength('mail', 255)
            ->allowEmptyString('mail');

        $validator
            ->scalar('mail_form_text')
            ->maxLength('mail_form_text', 255)
            ->notEmptyString('mail_form_text');

        $validator
            ->boolean('view_mail_form')
            ->notEmptyString('view_mail_form');

        $validator
            ->boolean('view_form_tel')
            ->notEmptyString('view_form_tel');

        $validator
            ->boolean('view_form_pattern')
            ->notEmptyString('view_form_pattern');

        $validator
            ->boolean('view_form_name_kana')
            ->notEmptyString('view_form_name_kana');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
