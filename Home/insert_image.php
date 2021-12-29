<?php
if (count($_FILES) > 0) {
    if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
        require_once "config.php";
        $imgData = addslashes(file_get_contents($_FILES['userImage']['tmp_name']));
        $imageProperties = getimagesize($_FILES['userImage']['tmp_name']);

        $sql = "INSERT INTO myfirstdatabase.space_image(`image`, image_type, spaceID) VALUES('{$imgData}', '{$imageProperties['mime']}', 6)";
        $current_id = $pdo->query($sql);
    }
}
?>
<HTML>
<HEAD>
<TITLE>Upload Image to MySQL BLOB</TITLE>
</HEAD>
<BODY>
    <form name="frmImage" enctype="multipart/form-data" action=""
        method="post" class="frmImageUpload">
        <label>Upload Image File:</label><br /> <input name="userImage"
            type="file" class="inputFile" /> <input type="submit"
            value="Submit" class="btnSubmit" />
    </form>
    </div>
</BODY>
</HTML>
