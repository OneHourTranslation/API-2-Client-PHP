<?php
	$sandbox = 'https://sandbox.onehourtranslation.com';
	$prod = 'https://www.onehourtranslation.com';

	$account_id = 269; //demo user
	$secret_key = '5a68561984276108fa42d7cffbbf91a5'; //demo user
	
	
	/*
	 * Switch between "$prod" to use production or "$sandbox" to use sandbox environment.
	 */
	$url = $sandbox;

?>
<style>
.apiMethod {
	float: left;
	border-bottom: 2px solid #c0c0c0;
	width: 100%;
	margin: 0 0 10px;
}
.apiForm {
	float: left;
	margin: 0 10px 10px;
	
}
.apiResFrame{
 	float: left;
 	margin: 0 10px;
}
	
</style>

<h1>OneHourTranslation API Test Console</h1>

<div class="apiMethod">
	<h2>Submit a New Project:</h2>
	<div class="apiForm">
		<form method="post" action="<?php echo $url?>/api/1/project/new/" target="resNewIframe">
			URL: <input type="text" name="url" value="<?php echo $url?>" readonly="readonly" disabled="disabled"/> <br />
			Account ID: <input type="text" name="account_id" value="<?php echo $account_id?>" /> <br />
			Secret Key: <input type="text" name="secret_key" value="<?php echo $secret_key?>"  /><br />
			Source: <input type="text" name="source" /><br />
			Target: <input type="text" name="target" /><br />
			Content: <textarea name="content" cols="50" rows="20"></textarea><br />
			Word Count: <input type="text" name="word_count" /><br />
			Notes: <input type="text" name="notes" /><br />
			Content Type: <input type="text" name="content_type" value="text/plain"/><br />
			Callback URL: <input type="text" name="callback_url" /><br />
			
			User Reference: <input type="text" name="user_reference" value=""/><br />
			Project Reference: <input type="text" name="project_reference" value=""/><br />
			<?php for($i=0;$i<10;$i++) : ?>
				Custom <?php echo $i?>: <input type="text" name="custom<?php echo $i?>" value=""/><br />
			<?php endfor?>
			
			<input type="submit" value="Submit a new Project"/>
		</form>
	</div>
	
	
	<div class="apiResFrame">
		<iframe name="resNewIframe" style="width: 400px;height: 300px;border: 2px solid black;"></iframe>
	</div>
</div>

<div class="apiMethod">
	<h2>Get Project Details:</h2>
	<div class="apiForm">
		<form method="post" action="<?php echo $url?>/api/1/project/" target="resProjDetailsIframe">
			URL: <input type="text" name="url" value="<?php echo $url?>" readonly="readonly" disabled="disabled"/> <br />
			Account ID: <input type="text" name="account_id" value="<?php echo $account_id?>" /> <br />
			Secret Key: <input type="text" name="secret_key" value="<?php echo $secret_key?>"  /><br />
			Project ID: <input type="text" name="project_id" id="pid"/><br />
			
			
			<input type="submit" value="Get Details" onclick="this.form.action = '<?php echo $url?>/api/1/project/'+this.form.project_id.value+'/details/'"/>
		</form>
	</div>
	
	
	<div class="apiResFrame">
		<iframe name="resProjDetailsIframe" style="width: 400px;height: 300px;border: 2px solid black;"></iframe>
	</div>
</div>

<div class="apiMethod">
	<h2>Get Project Contents:</h2>
	<div class="apiForm">
		<form method="post" action="<?php echo $url?>/api/1/project/" target="resProjContentsIframe">
			URL: <input type="text" name="url" value="<?php echo $url?>" readonly="readonly" disabled="disabled"/> <br />
			Account ID: <input type="text" name="account_id" value="<?php echo $account_id?>" /> <br />
			Secret Key: <input type="text" name="secret_key" value="<?php echo $secret_key?>"  /><br />
			Project ID: <input type="text" name="project_id" id="pid"/><br />
			
			
			<input type="submit" value="Get Contents" onclick="this.form.action = '<?php echo $url?>/api/1/project/'+this.form.project_id.value+'/contents/'"/>
		</form>
	</div>
	
	
	<div class="apiResFrame">
		<iframe name="resProjContentsIframe" style="width: 400px;height: 300px;border: 2px solid black;"></iframe>
	</div>
</div>

<div class="apiMethod">
	<h2>Get Account Details:</h2>
	<div class="apiForm">
		<form method="post" action="<?php echo $url?>/api/1/account/details/" target="resAccDetailsIframe">
			URL: <input type="text" name="url" value="<?php echo $url?>" readonly="readonly" disabled="disabled"/> <br />
			Account ID: <input type="text" name="account_id" value="<?php echo $account_id?>" /> <br />
			Secret Key: <input type="text" name="secret_key" value="<?php echo $secret_key?>"  /><br />

			<input type="submit" value="Get Details" />
		</form>
	</div>
	
	
	<div class="apiResFrame">
		<iframe name="resAccDetailsIframe" style="width: 400px;height: 300px;border: 2px solid black;"></iframe>
	</div>
</div>

<div class="apiMethod">
	<h2>Machine Translation:</h2>
	<div class="apiForm">
		<form method="post" action="<?php echo $url?>/api/1/mt/" target="resMTIframe">
			URL: <input type="text" name="url" value="<?php echo $url?>" readonly="readonly" disabled="disabled"/> <br />
			Account ID: <input type="text" name="account_id" value="<?php echo $account_id?>" /> <br />
			Secret Key: <input type="text" name="secret_key" value="<?php echo $secret_key?>"  /><br />
			Source: <input type="text" name="source" /><br />
			Target: <input type="text" name="target" /><br />
			Content: <textarea name="source_text" cols="50" rows="20"></textarea><br />
			<input type="submit" value="Translate" />
		</form>
	</div>
	
	
	<div class="apiResFrame">
		<iframe name="resMTIframe" style="width: 400px;height: 300px;border: 2px solid black;"></iframe>
	</div>
</div>