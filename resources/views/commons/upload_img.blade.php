<!-- 上传图片div /S-->
<div class="upload-mask">
</div>
<form class="panel panel-info upload-file">
    <div class="panel-heading">
        上传图片
        <span class="close pull-right">关闭</span>
    </div>
    <div class="panel-body">
        <div id="validation-errors"></div>
        <form action="/admin/upload_img" method="post" enctype=”multipart/form-data”>
            <div class="form-group">
                <label>图片上传</label>
                <span class="require">(*)</span>
                <input id="thumb" name="file" type="file" required="required">
                <input id="imgID" type="hidden" name="id" value="">

            </div>
        </form>
    </div>

<div class="panel-footer">
</div>
</div>

<!-- 上传图片div /E-->