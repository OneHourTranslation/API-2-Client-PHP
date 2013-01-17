<?php

require_once '../config/config.php';

/*
 * Switch between "$prod" to use production or "$sandbox" to use sandbox environment.
 */
$url = com\OHT\API\OHT_SANDBOX_URL;
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
        <form method="post" action="<?php echo $url ?>/projects/translation/" target="resNewIframe">
            URL: <input type="text" name="url" value="<?php echo $url ?>" readonly="readonly" disabled="disabled"/> <br />
            Public Key: <input type="text" name="public_key" value="<public key here>" /> <br />
            Secret Key: <input type="text" name="secret_key" value="<secret key here>"  /><br />
            Source: <input type="text" name="source_lang" /><br />
            Target: <input type="text" name="target_lang" /><br />
            Sources: <input type="text" name="sources" /><br />
            Word Count: <input type="text" name="word_count" /><br />
            Notes: <input type="text" name="notes" /><br />
            Callback URL: <input type="text" name="callback_url" /><br />

            User Reference: <input type="text" name="user_reference" value=""/><br />
            Project Reference: <input type="text" name="project_reference" value=""/><br />
            <?php for ($i = 0; $i < 10; $i++) : ?>
                Custom <?php echo $i ?>: <input type="text" name="custom<?php echo $i ?>" value=""/><br />
            <?php endfor ?>

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
        <form method="get" action="<?php echo $url ?>/projects/" target="resProjDetailsIframe">
            URL: <input type="text" name="url" value="<?php echo $url ?>" readonly="readonly" disabled="disabled"/> <br />
            Public Key: <input type="text" name="public_key" value="<public key here>" /> <br />
            Secret Key: <input type="text" name="secret_key" value="<secret key here>"  /><br />
            Project ID: <input type="text" name="pid" id="pid"/><br />
            <input type="submit" value="Get Details" onclick="this.form.action = '<?php echo $url ?>/projects/'+this.form.pid.value"/>
        </form>
    </div>


    <div class="apiResFrame">
        <iframe name="resProjDetailsIframe" style="width: 400px;height: 300px;border: 2px solid black;"></iframe>
    </div>
</div>

<div class="apiMethod">
    <h2>Submit a New Resource:</h2>
    <div class="apiForm">
        <form method="post" action="<?php echo $url ?>/resources/text/" target="resProjContentsIframe">
            URL: <input type="text" name="url" value="<?php echo $url ?>" readonly="readonly" disabled="disabled"/> <br />
            Public Key: <input type="text" name="public_key" value="<public key here>" /> <br />
            Secret Key: <input type="text" name="secret_key" value="<secret key here>"  /><br />
            Text: <input type="text" name="text" id="text"/><br />
            <input type="submit" value="Submit a New Resource"/>
        </form>
    </div>


    <div class="apiResFrame">
        <iframe name="resProjContentsIframe" style="width: 400px;height: 300px;border: 2px solid black;"></iframe>
    </div>
</div>

<div class="apiMethod">
    <h2>Get Account Details:</h2>
    <div class="apiForm">
        <form method="get" action="<?php echo $url ?>/accounts/" target="resAccDetailsIframe">
            URL: <input type="text" name="url" value="<?php echo $url ?>" readonly="readonly" disabled="disabled"/> <br />
            Public Key: <input type="text" name="public_key" value="<public key here>" /> <br />
            Secret Key: <input type="text" name="secret_key" value="<secret key here>"  /><br />

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
        <form method="post" action="<?php echo $url ?>/mt/translate/text" target="resMTIframe">
            URL: <input type="text" name="url" value="<?php echo $url ?>" readonly="readonly" disabled="disabled"/> <br />
            Public Key: <input type="text" name="public_key" value="<public key here>" /> <br />
            Secret Key: <input type="text" name="secret_key" value="<secret key here>"  /><br />
            Source: <input type="text" name="source_lang" /><br />
            Target: <input type="text" name="target_lang" /><br />
            Content: <textarea name="source_content" cols="50" rows="20"></textarea><br />
            <input type="submit" value="Translate" />
        </form>
    </div>


    <div class="apiResFrame">
        <iframe name="resMTIframe" style="width: 400px;height: 300px;border: 2px solid black;"></iframe>
    </div>
</div>
