<?php

use yii\helpers\StringHelper;


$dbName = $generator->getDbConnection()->driverName;
$information = new \petersonsilva\easyiigii\Informations();
$modelClass = $generator->modelClass;
$modelClassName = StringHelper::basename($generator->modelClass);
$pks = $generator->tableSchema->primaryKey;
$arrayfk = $generator->tableSchema->foreignKeys;
$tableSchema = $generator->tableSchema;
$model = new $modelClass;
$labels = $model->attributeLabels();
echo "<?php\n";
?>

    class Test<?= $modelClassName ?>Cest
    {
        public function _before(FunctionalTester $I){
        $I->login('superadmin','superadmin');
        }

        public function <?= $modelClassName . 'NotNullFieds' ?>(FunctionalTester $I)
        {
        $I->wantTo('Verify exception for not null fields');
        $I->amOnPage('<?= $tableSchema->fullName ?>/create');
        $I->submitForm('form',[]);
<?php foreach ($tableSchema->columns as $column): ?>
<?php if ($column->allowNull==false && $column->name!='id'): ?>
        $I->see('"<?= $labels[$column->name] ?>" não pode ficar em branco.');
<?php endif;?>
<?php endforeach;?>

        }

        public function <?= $modelClassName . 'IntegerFields' ?>(FunctionalTester $I)
        {
        $I->wantTo('Verify exception for integer fields');
        $I->amOnPage('<?= $tableSchema->fullName ?>/create');
        $I->submitForm('form',[
<?php foreach ($tableSchema->columns as $column): ?>
<?php if (!$column->isPrimaryKey && $column->phpType=='integer' ):?>
            <?= "'{$modelClassName}[{$column->name}]' => '{$labels[$column->name]} $modelClassName'" ?>,
<?php endif;?>
<?php endforeach;?>
        ]);

<?php foreach ($tableSchema->columns as $column): ?>
<?php if ($column->phpType=='integer' && $column->allowNull==true ):?>
        $I->see('"<?= $labels[$column->name] ?>" deve ser um número inteiro.');
<?php endif;?>
<?php endforeach;?>

        }

        public function <?= $modelClassName . 'fkFields' ?>(FunctionalTester $I)
        {
        $I->wantTo('Verify exception for fk fields');
        $I->amOnPage('<?= $tableSchema->fullName ?>/create');
        $I->submitForm('form',[

<?php foreach ($arrayfk as $fk): ?>
<?php $col = array_search("id", $fk)?>
            <?= "'{$modelClassName}[{$col}]' => '99999999'" ?>,
<?php endforeach;?>
        ]);

<?php foreach ($arrayfk as $fk): ?>
<?php $col = array_search("id", $fk)?>
        $I->see('"<?= $labels[$col] ?>" é inválido');
<?php endforeach;?>

}



}
