<?php




}

//アップロードがあった
if (is_uploaded_file($_FILES['image']['tmp_name'])) {

  $rename_org = "1";

  if(!isset($_FILES['image']['error']) || !is_int($_FILES['image']['error'])){
    throw new ErrorException('パラメータが不正です');
  }
  
  // $_FILES['image']['error'] の値を確認
  switch ($_FILES['image']['error']) {
    case UPLOAD_ERR_OK: // OK
        break;
    case UPLOAD_ERR_NO_FILE:   // ファイル未選択
        throw new ErrorException('ファイルが選択されていません');
    case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
    case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過 (設定した場合のみ)
        throw new ErrorException('ファイルサイズが大きすぎます');
    default:
        throw new ErrorException('その他のエラーが発生しました');
  }


  // ここで定義するサイズ上限のオーバーチェック
  if ($_FILES['image']['size'] > 10485760) {//
      throw new ErrorException('ファイルサイズが大きすぎます');
  }


  if (!$ext = array_search(
    mime_content_type($_FILES['image']['tmp_name']),
    array(
      'gif' => 'image/gif',
      'jpg' => 'image/jpeg',
      'png' => 'image/png',
    ),
    true
  )) {
    throw new ErrorException('ファイル形式が不正です');
  }
  
  if (!move_uploaded_file(
    $_FILES['image']['tmp_name'], 
    "./".$rename_org
    )
  ) {
    throw new ErrorException('ファイル保存時にエラーが発生しました');
  }

  $fileimagename = $rename_org;

  // ファイルのパーミッションを確実に0644に設定する
  chmod("./".$rename_org, 0644);

  list($wid, $hei, $type) = getimagesize("./".$rename_org);

  if($wid >= 150 || $hei >= 150){
    $wh = ratio($wid, $hei);

    img_resize(
      "./".$rename_org, 
      $wh['width'], $wh['height'],
      $wid, $hei,
      0, 0, $rename_org);
  }

  $image_change = true;
}elseif(isset($_POST["img_d"])){
  $fileimagename = "";
  $image_change = true;
}else{
  $fileimagename = "";
}
?>

<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="image">画像</label>

    <input type="file" name="image" id="image"
      accept=".jpg,.gif,.png,image/gif,image/jpeg,image/png">
    <div>ファイルサイズ10MB以内</div>

    <div id="already" style="display: none;">
      <img id="preview">
      <a href="javascript:void(0)" id="imagedel">
        <img src="/img/icon/batu.jpg">
      </a>
    </div>
    

    <canvas id="trimed_image" style="display: none;"></canvas>

    <input type="hidden" name="" id="imagedel_send" value="del">

    <button type="submit">投稿する</button>
  </div>
</form>