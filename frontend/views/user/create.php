<?php

/* @var $model frontend\models\UserCreateForm[] */
?>

<div id="user-create">
    <button type="button" id="addForm" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i></button>
    <?php $form = \yii\widgets\ActiveForm::begin(); ?>

    <?= \yii\helpers\Html::submitButton('Save', ['class' => 'btn btn-success', 'id' => 'save']) ?>
    <?php \yii\widgets\ActiveForm::end(); ?>
</div>

<?php
$this->registerJs('
    var id = 0;
    
    $("#addForm").on("click", function() {
        $.ajax({
            method: "POST",
            async: true,
            cache: false,
            url: "index.php?r=user/form",
            dataType: "html",
            data: {
                "id": id,
            }
        }).done(function(data) {
            $(data).insertBefore("#save");
            id++;
        }).fail(function() {
            console.log("Could not fetch the form");
        });
    });
    
    $("#save").on("click", function (e) {
        var ok = 0;
        e.preventDefault();
        $("#" + "' . $form->id . '" + " :input").each(function() {
            console.log($(this).attr("type"));
            switch ($(this).attr("type")) {
                case "text": {
                    $(this).siblings(".help-block").text("");
                    $(this).closest(".form-group").removeClass("has-error");
                    
                    if (!$(this).val()) {
                        ok = 1;
                        $(this).siblings(".help-block").text("This field is required").show();
                        $(this).closest(".form-group").addClass("has-error");
                    }
                    break;
                }
                case "email": {
                    $(this).siblings(".help-block").text("");
                    $(this).closest(".form-group").removeClass("has-error");
                    
                    if (!$(this).val()) {
                        ok = 1;
                        $(this).siblings(".help-block").text("This field is required").show();
                        $(this).closest(".form-group").addClass("has-error");
                    } else if (!validateEmail($(this).val())) {
                        ok = 1;
                        $(this).siblings(".help-block").text("This is not a valid email").show();
                        $(this).closest(".form-group").addClass("has-error");
                    }
                    break;
                }
                case "password": {
                    $(this).siblings(".help-block").text("");
                    $(this).closest(".form-group").removeClass("has-error");
                    
                    if (!$(this).val()) {
                        ok = 1;
                        $(this).siblings(".help-block").text("This field is required").show();
                        $(this).closest(".form-group").addClass("has-error");
                    }
                    break;
                }
                case "default": {
                    break;
                }
            }
        });
        if (ok == 0) {
            $("#" + "' . $form->id . '").submit();
        }
    });
    
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
');
?>