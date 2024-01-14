<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sites Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Site newEmptyEntity()
 * @method \App\Model\Entity\Site newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Site[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Site get($primaryKey, $options = [])
 * @method \App\Model\Entity\Site findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Site patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Site[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Site|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Site saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Site[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Site[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Site[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Site[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SitesTable extends Table
{

    /**
     * 画像ファイルパス
     */
    // 画像表示用のパス
    const FAVICON_PATH = 'users/favicon/';
    // ルートからの相対パス
    const ROOT_FAVICON_PATH = WWW_ROOT . 'img/' . self::FAVICON_PATH;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('sites');
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
            ->scalar('site_title')
            ->maxLength('site_title', 50, 'サイトタイトルは50文字以内で入力してください。')
            ->allowEmptyString('site_title');

        $validator
            ->scalar('site_description')
            ->maxLength('site_description', 400, 'サイトディスクリプションは400文字以内で入力してください。')
            ->allowEmptyString('site_description');

        $validator
            ->scalar('histories_title')
            ->notBlank('histories_title', '経歴のタイトル名は必須です。')
            ->maxLength('histories_title', 50, '経歴のタイトル名は50文字以内で入力してください。');

        $validator
            ->scalar('works_title')
            ->notBlank('works_title', '実績のタイトル名は必須です。')
            ->maxLength('works_title', 50, '実績のタイトル名は50文字以内で入力してください。');

        $validator
            ->scalar('others_title')
            ->notBlank('others_title', 'その他のタイトル名は必須です。')
            ->maxLength('others_title', 50, 'その他のタイトル名は50文字以内で入力してください。');

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
