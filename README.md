#Amos Invitations

System to invite external people to platform

## Installation

### 1. The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
composer require open20/amos-invitations
```

or add this row

```
"open20/amos-invitations": "dev-master"
```

to the require section of your `composer.json` file.


### 2. Add module to your main config in backend:
	
```php
'modules' => [
    'invitations' => [
        'class' => 'open20\amos\invitations\Module',
    ],
],
```


### 3. Apply migrations

```bash
php yii migrate/up --migrationPath=@vendor/open20/amos-invitations/src/migrations
```

or add this row to your migrations config in console:

```php
return [
    '@vendor/open20/amos-invitations/src/migrations',
];
```

## Widgets

#Invite user Widget
InviteUserWidget draws a button that open a modal cointaining the user invitation form.
The widget can be used also in another model form, splitting the button and the modal form parts (to avoid form cointaining another form). 
 
example in a view : 
```
 <?= InviteUserWidget::widget([]) ?>
```
 
example in a form:
```
<?php ActiveForm::begin() ?>
 <?= InviteUserWidget::widget(['layout' => '{invitationBtn}' ]) ?>
<?php ActiveForm::end(); ?>
<?= InviteUserWidget::widget(['layout' => '{invitationModalForm}' ]) ?>
```

### Module configuration params

* **subjectPlaceholder** - string, default = '#subject-invite' 
String used for placeholder which translates mail subject.

* **subjectCategory** - string, default = 'amosinvitations' 
String used for linking a translation category to the mail subject.
