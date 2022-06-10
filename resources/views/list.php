<?php 
defined('EXEC') or die; 
require_once "././src/Factor.php";
require_once "././controllers/Sort.php";
$getSorts = new Sort;
$user = $_SESSION['user'];
$_SESSION["set_token"] = md5(uniqid().time());

$db = Factor::getDB();
$db->table('contacts');
$db->select("COUNT(*)");
$db->setDB();
$count = $db->loadResult();

if($count){
    $db = Factor::getDB();
    $db->table('contacts');
    $db->order($desk.' DESC');
    $db->setDB();
    $contacts = $db->loadAssocList('id');
}
?>
<link rel="stylesheet" href="/<?php echo constant("SERVER"); ?>/resources/css/form.css">
<link rel="stylesheet" href="/<?php echo constant("SERVER"); ?>/resources/css/list.css">

<form method="post">
    <input type="hidden" name="get_token" value="<?=$_SESSION["set_token"];?>">
    <input type="hidden" name="ctrl" value="User.logout">
    <button type="submit" name="is_submit" class="logout-btn">Logout</button>
</form>

<?php if($user['permission']){ ?>
<div class="form-container form-contact" >
    <form class="form-form" method="post">
        <input type="hidden" name="get_token" value="<?=$_SESSION["set_token"];?>">
        <?php if(is_array($contactData)){ ?> <input type="hidden" name="set_id" value="<?=$contactData['id'];?>"> <?php } ?>
        <input type="hidden" name="ctrl" value="Contacts.<?php if(is_array($contactData)){ echo "update"; }else{ echo "add"; } ?>">
        <input class="form-line" type="text" required="required" aria-required="true" <?php if(is_array($contactData)){ ?> value="<?=$contactData['name'];?>" <?php } ?> min="3" max="45" name="name" placeholder="name">
        <input class="form-line" type="text" required="required" aria-required="true" <?php if(is_array($contactData)){ ?> value="<?=$contactData['surname'];?>" <?php } ?> min="3" max="45" name="surname" placeholder="surname">
        <input class="form-line" type="tel" required="required" aria-required="true" <?php if(is_array($contactData)){ ?> value="<?=$contactData['phone'];?>" <?php } ?> min="3" max="255" name="tel" placeholder="phone">
        <input class="form-line" type="email" required="required" aria-required="true" <?php if(is_array($contactData)){ ?> value="<?=$contactData['email'];?>" <?php } ?> min="3" max="255" name="email" placeholder="e-mail">
        <input type="submit" class="form-submit" align="center" name="is_submit" value="<?php if(is_array($contactData)){ echo "Update"; }else{ echo "Insert"; } ?>">
    </form>
</div>


<form method="post" id="contact-form">
    <input type="hidden" name="get_token" value="<?=$_SESSION["set_token"];?>">
    <input type="hidden" name="ctrl">
    <input type="hidden" name="set_id">
    <input type="hidden" name="is_submit" value="1">
</form>
<?php } ?>
 
<?php if($count){ ?>
<div class="head-tab">
    <form method="post" id="formSort">
        <input type="hidden" name="get_token" value="<?=$_SESSION["set_token"];?>">
        <input type="hidden" name="ctrl" value="Sort.sortBy">
        <input type="hidden" name="sort_value" value="<?=$desk;?>">
        <input type="hidden" name="is_submit" value="1">
    </form>
    
    <select id="selectSort" class="selectSort">
        <?php foreach ($getSorts->sort_array as $by => $sort_by) { ?>
        <option <?php if($desk == $by){ ?> selected <?php } ?> value="<?=$by;?>"><?=$sort_by;?></option>
        <?php }?>
    </select>
    
    <input type="text" id="selectContact" placeholder="Search.." class="selectSort">
</div>
<?php } ?>

<div class='contacts'>
<?php if($count){ 
    foreach ($contacts as $id => $contact) { ?>
        <div class='contact-table'>
            <div class='qt-contact'>
                <?php if($user['permission']){ ?>
                    <div class="btn-list">
                        <p><?=$contact['name']." ".$contact['surname'];?></p>
                        <button type="button" class="btn update_contact" data-target="<?=$contact['id'];?>"><img src="/<?php echo constant("SERVER"); ?>/resources/images/update.png"" alt="img-btn" class="btn_img"></button>
                        <button type="button" data-target="<?=$contact['id'];?>" class="btn is_delete_contact"><img src="/<?php echo constant("SERVER"); ?>/resources/images/delete.png"" alt="img-btn" class="btn_img"></button>
                    </div>
                <?php } ?>
            </div>

            <ul class='qt-items' id='items_0'>
                <li class='qt-item'>
                    <span class='qt-item-variant'>Name: <?=$contact['name'];?></span>
                </li>
                <li class='qt-item'>
                    <span class='qt-item-variant'>Surname: <?=$contact['surname'];?></span>
                </li>
                <li class='qt-item'>
                    <span class='qt-item-variant'>Phone: <?=$contact['phone'];?></span>
                </li>
                <li class='qt-item'>
                    <span class='qt-item-variant'>Email: <?=$contact['email'];?></span>
                </li>
                <li class='qt-item'>
                    <span class='qt-item-variant'>Created: <?=date("Y:m:d H:i:s", $contact['created_at']);?></span>
                </li>
                <?php if($contact['updated_at']){ ?>
                <li class='qt-item'>
                    <span class='qt-item-variant'>Updated: <?=date("Y:m:d H:i:s", $contact['updated_at']);?></span>
                </li>
                <?php } ?>
            </ul>
        </div>
        <?php if($user['permission']){ ?>
            <div style="display:none" id="detele_content-<?=$contact['id'];?>" class="profile-modal">
                <div class="pm-content">
                    <span class="close_btn close-content_delete">x</span>
                    <p>
                        Are you sure you want to delete this contact?
                        <button type="button" class="delete_contact" data-target="<?=$contact['id'];?>">YES</button>
                    </p>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php }
} ?>
</div>
