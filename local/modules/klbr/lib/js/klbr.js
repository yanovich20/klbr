document.addEventListener("DOMContentLoaded",function(){
    console.log("hello world!");
    const containerForButton = document.querySelector(".product-item-detail-pay-block");
    if(!containerForButton)
        return false;
    let button = document.createElement('button');
    button.classList.add("btn");
    button.classList.add("btn-info");
    button.classList.add("btn-quick-order");
    button.textContent = "Быстрый заказ";
    containerForButton.appendChild(button);
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
            autoHide: true,
            buttons: [
                new BX.PopupWindowButton({
                   text: "Купить" ,
                   className: "popup-window-button-accept" ,
                   events: {click: async function(){
                    console.log(email);
                    if(!checkEmail(email.value))
                    {
                        alert("Неверный email адрес!");
                        return;
                    }
                    if(!checkPhone(phone.value)){
                        alert("Неверный номер телефона!");
                        return;
                    }
                    if(!checkName(name.value)){
                        alert("Неверное название!");
                    }
                    
                    
                    let data ={
                        "UF_NAME":name.value,
                        "UF_PHONE":phone.value,
                        "UF_EMAIL":email.value,
                        "UF_COMMENT":comment.value
                    };
                    response = await BX.ajax.runComponentAction("klbr:grid","addQuickOrder",{mode:'class',data:data});
                    console.log(response);
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
             if(!checkEmail(email.value))
                alert("Неверный email адрес!");
        })
        phone.addEventListener("change",function(){
            if(!checkPhone(phone.value)){
                alert("Неверный номер телефона!");
            }
        })
        name.addEventListener("change",function(){
            if(!checkName(name.value)){
                alert("Неверное название!");
            }
        })
    })
    function checkEmail(value){
        const regex = /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu;
        return regex.test(value);
    }
    function checkPhone(value){
        const regex = /^[\+][0-9]{10}$/;
        return regex.test(value);
    }
    function checkName(value)
    {
        const regex = /^[\w]{2,}$/;
        return regex.test(value);
    }
})