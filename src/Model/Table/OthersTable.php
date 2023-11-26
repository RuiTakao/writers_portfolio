<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Others Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Other newEmptyEntity()
 * @method \App\Model\Entity\Other newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Other[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Other get($primaryKey, $options = [])
 * @method \App\Model\Entity\Other findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Other patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Other[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Other|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Other saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Other[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Other[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Other[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Other[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OthersTable extends Table
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

        $this->setTable('others');
        $this->setDisplayField('title');
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
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('content1')
            ->maxLength('content1', 50)
            ->allowEmptyString('content1');

        $validator
            ->scalar('content2')
            ->maxLength('content2', 50)
            ->allowEmptyString('content2');

        $validator
            ->scalar('content3')
            ->maxLength('content3', 50)
            ->allowEmptyString('content3');

        $validator
            ->scalar('content4')
            ->maxLength('content4', 50)
            ->allowEmptyString('content4');

        $validator
            ->scalar('content5')
            ->maxLength('content5', 50)
            ->allowEmptyString('content5');

        $validator
            ->scalar('content6')
            ->maxLength('content6', 50)
            ->allowEmptyString('content6');

        $validator
            ->scalar('content7')
            ->maxLength('content7', 50)
            ->allowEmptyString('content7');

        $validator
            ->scalar('content8')
            ->maxLength('content8', 50)
            ->allowEmptyString('content8');

        $validator
            ->scalar('content9')
            ->maxLength('content9', 50)
            ->allowEmptyString('content9');

        $validator
            ->scalar('content10')
            ->maxLength('content10', 50)
            ->allowEmptyString('content10');

        $validator
            ->scalar('others_order')
            ->maxLength('others_order', 5)
            ->requirePresence('others_order', 'create')
            ->notEmptyString('others_order');

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
