<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Histories Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\History newEmptyEntity()
 * @method \App\Model\Entity\History newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\History[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\History get($primaryKey, $options = [])
 * @method \App\Model\Entity\History findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\History patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\History[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\History|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\History saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\History[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\History[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\History[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\History[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HistoriesTable extends Table
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

        $this->setTable('histories');
        $this->setDisplayField('id');
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
            ->scalar('title')
            ->maxLength('title', 50)
            ->allowEmptyString('title');

        $validator
            ->scalar('overview')
            ->maxLength('overview', 255)
            ->allowEmptyString('overview');

        $validator
            ->scalar('start')
            ->maxLength('start', 50)
            ->allowEmptyString('start');

        $validator
            ->scalar('end')
            ->maxLength('end', 50)
            ->allowEmptyString('end');

        $validator
            ->scalar('title2')
            ->maxLength('title2', 50)
            ->allowEmptyString('title2');

        $validator
            ->scalar('overview2')
            ->maxLength('overview2', 255)
            ->allowEmptyString('overview2');

        $validator
            ->scalar('start2')
            ->maxLength('start2', 50)
            ->allowEmptyString('start2');

        $validator
            ->scalar('end2')
            ->maxLength('end2', 50)
            ->allowEmptyString('end2');

        $validator
            ->scalar('title3')
            ->maxLength('title3', 50)
            ->allowEmptyString('title3');

        $validator
            ->scalar('overview3')
            ->maxLength('overview3', 255)
            ->allowEmptyString('overview3');

        $validator
            ->scalar('start3')
            ->maxLength('start3', 50)
            ->allowEmptyString('start3');

        $validator
            ->scalar('end3')
            ->maxLength('end3', 50)
            ->allowEmptyString('end3');

        $validator
            ->scalar('title4')
            ->maxLength('title4', 50)
            ->allowEmptyString('title4');

        $validator
            ->scalar('overview4')
            ->maxLength('overview4', 255)
            ->allowEmptyString('overview4');

        $validator
            ->scalar('start4')
            ->maxLength('start4', 50)
            ->allowEmptyString('start4');

        $validator
            ->scalar('end4')
            ->maxLength('end4', 50)
            ->allowEmptyString('end4');

        $validator
            ->scalar('title5')
            ->maxLength('title5', 50)
            ->allowEmptyString('title5');

        $validator
            ->scalar('overview5')
            ->maxLength('overview5', 255)
            ->allowEmptyString('overview5');

        $validator
            ->scalar('start5')
            ->maxLength('start5', 50)
            ->allowEmptyString('start5');

        $validator
            ->scalar('end5')
            ->maxLength('end5', 50)
            ->allowEmptyString('end5');

        $validator
            ->scalar('title6')
            ->maxLength('title6', 50)
            ->allowEmptyString('title6');

        $validator
            ->scalar('overview6')
            ->maxLength('overview6', 255)
            ->allowEmptyString('overview6');

        $validator
            ->scalar('start6')
            ->maxLength('start6', 50)
            ->allowEmptyString('start6');

        $validator
            ->scalar('end6')
            ->maxLength('end6', 50)
            ->allowEmptyString('end6');

        $validator
            ->scalar('title7')
            ->maxLength('title7', 50)
            ->allowEmptyString('title7');

        $validator
            ->scalar('overview7')
            ->maxLength('overview7', 255)
            ->allowEmptyString('overview7');

        $validator
            ->scalar('start7')
            ->maxLength('start7', 50)
            ->allowEmptyString('start7');

        $validator
            ->scalar('end7')
            ->maxLength('end7', 50)
            ->allowEmptyString('end7');

        $validator
            ->scalar('title8')
            ->maxLength('title8', 50)
            ->allowEmptyString('title8');

        $validator
            ->scalar('overview8')
            ->maxLength('overview8', 255)
            ->allowEmptyString('overview8');

        $validator
            ->scalar('start8')
            ->maxLength('start8', 50)
            ->allowEmptyString('start8');

        $validator
            ->scalar('end8')
            ->maxLength('end8', 50)
            ->allowEmptyString('end8');

        $validator
            ->scalar('title9')
            ->maxLength('title9', 50)
            ->allowEmptyString('title9');

        $validator
            ->scalar('overview9')
            ->maxLength('overview9', 255)
            ->allowEmptyString('overview9');

        $validator
            ->scalar('start9')
            ->maxLength('start9', 50)
            ->allowEmptyString('start9');

        $validator
            ->scalar('end9')
            ->maxLength('end9', 50)
            ->allowEmptyString('end9');

        $validator
            ->scalar('title10')
            ->maxLength('title10', 50)
            ->allowEmptyString('title10');

        $validator
            ->scalar('overview10')
            ->maxLength('overview10', 255)
            ->allowEmptyString('overview10');

        $validator
            ->scalar('start10')
            ->maxLength('start10', 50)
            ->allowEmptyString('start10');

        $validator
            ->scalar('end10')
            ->maxLength('end10', 50)
            ->allowEmptyString('end10');

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
