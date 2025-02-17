document.addEventListener("DOMContentLoaded",function(){
    const containerForButton = document.querySelector(".product-item-detail-pay-block");
    if(!containerForButton)
        return false;
    let div =document.createElement("div");
    div.classList.add("mb-3");
    div.classList.add("quick-order-div");
    let button = document.createElement('button');
    button.classList.add("btn");
    button.classList.add("btn-info");
    button.classList.add("btn-quick-order");
    button.textContent = "Быстрый заказ";
    div.appendChild(button);
    containerForButton.appendChild(div);
    button = document.querySelector(".btn-quick-order");
    let contentModalButton =`
    <div class="modal-container">
        <label class="modal-label">Имя</label>
        <input type='text' class="modal-input" name="NAME"/>
        <label class="modal-label">EMAIL</label>
        <input type='text' class="modal-input" name="EMAIL"/>
        <label class="modal-label">Телефон</label>
        <input type='text' class="modal-input" name="PHONE"/>
        <label class="modal-label">Комментарий</label>
        <input type='text' class="modal-input" name="COMMENT"/>
    </div>
    `
    button.addEventListener("click",function(){
        
        var popup = BX.PopupWindowManager.create("popup-message", null, {
            closeIcon: true,
            content: contentModalButton,
            darkMode: true,
            autoHide: false,
            buttons: [
                new BX.PopupWindowButton({
                   text: "Купить" ,
                   className: "popup-window-button-accept" ,
                   events: {click: async function(){
                    let error = false;
                    if(!checkEmailHandler(email))
                        error = true;
                    
                    if(!checkNameHandler(name))
                        error = true;
                    if(!checkPhoneHandler(phone))
                        error = true;
                    if(error)
                        return;
                    let clearPhone = phone.value.replace(/\D/g,"");
                    let normalizedPhone = "+"+clearPhone;
                    let data ={
                        "UF_NAME":name.value,
                        "UF_PHONE":normalizedPhone,
                        "UF_EMAIL":email.value,
                        "UF_COMMENT":comment.value
                    };
                    try{
                    response = await BX.ajax.runComponentAction("klbr:grid","addQuickOrder",{mode:'class',data:data});
                    }
                    catch(errorResult)
                    {
                        if(errorResult.status=="error")
                        {
                            messageToDisplay = "";
                            errorResult.errors.forEach(function(errorObject){
                                messageToDisplay += " " + errorObject.message;
                            })
                            showMessage(messageToDisplay);
                            return;
                        }
                    }
                    console.log(response);
                    
                    if(response.status==="error" && response.data.status=="error")
                    {
                        showMessage(response.data.message);
                        return;
                    }
                    showMessage("Данные сохранены");
                    this.popupWindow.close();
                   }}
                }),
                new BX.PopupWindowButton({
                   text: "Отмена" ,
                   className: "webform-button-link-cancel" ,
                   events: {click: function(){
                      this.popupWindow.close();
                   }}
                })
             ]
        });
        popup.show();

        const container = document.querySelector(".modal-container");
        const email = container.querySelector("[name='EMAIL']");
        const name = container.querySelector("[name='NAME']");
        const phone = container.querySelector("[name='PHONE']");
        const comment = container.querySelector("[name='COMMENT']");

        email.addEventListener("change",function(){
             checkEmailHandler(email);
        })
       
        name.addEventListener("change",function(){
            checkNameHandler(name);
        })

        $.fn.setCursorPosition = function(pos) {
            if ($(this).get(0).setSelectionRange) {
              $(this).get(0).setSelectionRange(pos, pos);
            } else if ($(this).get(0).createTextRange) {
              var range = $(this).get(0).createTextRange();
              range.collapse(true);
              range.moveEnd('character', pos);
              range.moveStart('character', pos);
              range.select();
            }
          };
        $("[name='PHONE']")
        .on("click",function(){$(this).setCursorPosition(3);})
        .on("change",function(){checkPhoneHandler(phone)})
        .mask("+7(999) 999-9999",{autoclear: false});
    })
    function checkNameHandler(name,message="Неверное название!"){
        if(!checkName(name.value)){
            setError(name,message);
            return false;
        }
        else
        {
            resetError(name);
            return true;
        }
    }
    function checkPhoneHandler(phone, message="Неверный номер телефона!"){
        if(!checkPhone(phone.value)){
            setError(phone,message);
            return false;
        }
        else
        {
            resetError(phone);
            return true;
        }
    }
    function checkEmailHandler(email,message="Неверный email!"){
        if(!checkEmail(email.value))
        {
            setError(email,message);
            return false;
        }
        else{
            resetError(email);
            return true;
        }
    }
    function checkEmail(value){
        const regex = /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu;
        return regex.test(value);
    }
    function checkPhone(value){
        let clearPhone = value.replace(/\D/g,"");
        const regex = /^[0-9]{11}$/;
        return regex.test(clearPhone);
    }
    function checkName(value)
    {
        const regex = /^[a-zA-Zа-яёА-ЯЁ0-9 _]{2,}$/;
        return regex.test(value);
    }
    function setError(node,message)
    {
        if(!node.classList.contains("error"))
        {
            node.classList.add("error");
        }
        showMessage(message);
    }
    function resetError(node){
        node.classList.remove("error");
    }
    function showMessage(message){
        const messageBox = new BX.UI.Dialogs.MessageBox(
            {
                message: message,
                title: "Информационное сообщение",
                buttons: BX.UI.Dialogs.MessageBoxButtons.OK,
                okCaption: "OK",
            });
            messageBox.show();
    }
})