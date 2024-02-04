<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Profiles Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Profile newEmptyEntity()
 * @method \App\Model\Entity\Profile newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Profile[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Profile get($primaryKey, $options = [])
 * @method \App\Model\Entity\Profile findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Profile patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Profile[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Profile|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Profile saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Profile[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Profile[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Profile[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Profile[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProfilesTable extends Table
{

    /**
     * メッセージ
     */
    const INVALID_EXTENSION_MESSAGE = '拡張子が無効です。';

    /**
     * 画像ファイルパス
     */
    // 画像表示用のパス
    const PROFILE_IMAGE_PATH = 'users/profiles/';
    // ルートからの相対パス
    const ROOT_PROFILE_IMAGE_PATH = WWW_ROOT . 'img/' . self::PROFILE_IMAGE_PATH;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('profiles');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->maxLength('view_name', 50, '名前（表示名）は50文字以内で入力してください。')
            ->allowEmptyString('view_name');

        $validator
            ->maxLength('works', 50, '肩書（仕事名）は50文字以内で入力してください。')
            ->allowEmptyString('works');

        $validator
            ->maxLength('profile_text', 400, 'プロフィール文は400文字以内で入力してください。')
            ->allowEmptyString('profile_text');

        $validator
            ->maxLength('image_path', 255)
            ->allowEmptyString('image_path');

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
