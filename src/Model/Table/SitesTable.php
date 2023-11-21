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
     * メッセージ
     */
    const SUCCESS_MESSAGE = 'サイトの設定を変更しました。';
    const SUCCESS_FAVICON_MESSAGE = 'ファビコンを変更しました。';
    const SUCCESS_HEADER_IMAGE_MESSAGE = 'ヘッダー画像を変更しました。';
    const INVALID_MESSAGE = 'サイトの設定の変更に失敗しました。';
    const INVALID_FAVICON_MESSAGE = 'ファビコンの変更に失敗しました。';
    const INVALID_HEADER_IMAGE_MESSAGE = 'ヘッダー画像の変更に失敗しました。';
    const INVALID_INPUT_MESSEGE = '入力に不備があります。';
    const INVALID_EXTENSION_MESSAGE = '拡張子が無効です。';

    /**
     * 画像ファイルパス
     */
    // 画像表示用のパス
    const FAVICON_PATH = 'users/sites/favicons/';
    const HEADER_IMAGE_PATH = 'users/sites/headers/';
    // ルートからの相対パス
    const ROOT_FAVICON_PATH = WWW_ROOT . 'img/' . self::FAVICON_PATH;
    const ROOT_HEADER_IMAGE_PATH = WWW_ROOT . 'img/' . self::HEADER_IMAGE_PATH;
    // ブランク画像のパス
    const BLANK_FAVICON_PATH = 'blank/sites/favicons/favicon_blank.jpg';
    const BLANK_HEADER_IMAGE_PATH = 'blank/sites/headers/header_blank_image.jpg';

    /**
     * 画像の拡張子
     */
    const EXTENTIONS = [
        'jpg',
        'png',
        'jpeg',
        'webp'
    ];

    /**
     * ファビコンの拡張子
     */
    const FAVICON_EXTENTIONS = [
        'jpg',
        'png',
        'jpeg',
        'webp',
        'ico',
        'svg'
    ];

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
            ->maxLength('site_title', 50)
            ->allowEmptyString('site_title');

        $validator
            ->scalar('site_description')
            ->maxLength('site_description', 255)
            ->allowEmptyString('site_description');

        $validator
            ->scalar('favicon_path')
            ->maxLength('favicon_path', 255)
            ->allowEmptyString('favicon_path');

        $validator
            ->scalar('header_image_path')
            ->maxLength('heaher_image_path', 255)
            ->allowEmptyFile('heaher_image_path');

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
