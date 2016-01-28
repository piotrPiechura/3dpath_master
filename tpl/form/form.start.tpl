<form
	{if $_currentForm->getMethod() == 'post'}enctype="multipart/form-data"{/if}
	action="{$_currentForm->getActionHTML()}"
	method="{$_currentForm->getMethod()}"
	{if $_currentFormId}id="{$_currentFormId}"{/if}
>
	<fieldset>