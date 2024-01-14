<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Designs Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Design newEmptyEntity()
 * @method \App\Model\Entity\Design newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Design[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Design get($primaryKey, $options = [])
 * @method \App\Model\Entity\Design findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Design patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Design[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Design|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Design saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Design[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Design[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Design[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Design[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DesignsTable extends Table
{

    /**
     * TOPのレイアウト
     */
    // TOPのレイアウト
    const FV_DESIGN_PATTERN_DEFAULT = 1;
    const FV_DESIGN_PATTERN_X_TEXT_FIRST = 2;
    const FV_DESIGN_PATTERN_X_ICON_FIRST = 3;

    // TOPのレイアウトリスト
    const FV_DESIGN_PATTERN_LIST = [
        self::FV_DESIGN_PATTERN_DEFAULT,
        self::FV_DESIGN_PATTERN_X_TEXT_FIRST,
    ];

    // TOPレイアウトセッティングOK
    const FV_DESIGN_SETTING_PERMISSION = [
        self::FV_DESIGN_PATTERN_DEFAULT,
    ];

    // TOPのレイアウトのテキスト
    const FV_DESIGN_TEXT = [
        self::FV_DESIGN_PATTERN_DEFAULT => "デフォルトのTOPレイアウト",
        self::FV_DESIGN_PATTERN_X_TEXT_FIRST => "Xのヘッダーを使用したい人向けのレイアウト\nテキストを最初にするレイアウト",
    ];

    // TOPのレイアウトのパス
    const FV_DESIGN_PATH = 'top_layout/';

    /**
     * TOP画像
     */
    const FV_IMAGE_PC_PATH = 'users/fv_pc/';
    const ROOT_FV_IMAGE_PC_PATH = WWW_ROOT . 'img/' . self::FV_IMAGE_PC_PATH;
    const FV_IMAGE_SP_PATH = 'users/fv_sp/';
    const ROOT_FV_IMAGE_SP_PATH = WWW_ROOT . 'img/' . self::FV_IMAGE_SP_PATH;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('designs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

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
            ->integer('fv_design')
            ->allowEmptyString('fv_design');

        $validator
            ->scalar('fv_image_path')
            ->maxLength('fv_image_path', 255)
            ->allowEmptyFile('fv_image_path');

        $validator
            ->integer('fv_image_positionX')
            ->notEmptyFile('fv_image_positionX');

        $validator
            ->integer('fv_image_positionY')
            ->notEmptyFile('fv_image_positionY');

        $validator
            ->scalar('fv_image_sp_path')
            ->maxLength('fv_image_sp_path', 255)
            ->allowEmptyFile('fv_image_sp_path');

        $validator
            ->integer('fv_image_sp_positionX')
            ->notEmptyFile('fv_image_sp_positionX');

        $validator
            ->integer('fv_image_sp_positionY')
            ->notEmptyFile('fv_image_sp_positionY');

        $validator
            ->scalar('body_color')
            ->maxLength('body_color', 10)
            ->allowEmptyString('body_color');

        $validator
            ->scalar('title_color')
            ->maxLength('title_color', 10)
            ->allowEmptyString('title_color');

        $validator
            ->scalar('title_border_color')
            ->maxLength('title_border_color', 10)
            ->allowEmptyString('title_border_color');

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
