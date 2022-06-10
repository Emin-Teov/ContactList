<?php defined('EXEC') or die; ?>
    <script>
        const body = document.querySelector("body");
        const formSort = document.querySelector("#formSort");
        const selectSort = document.querySelector("#selectSort");
        const contacts = document.querySelectorAll(".contact-table");
        const selectContact = document.querySelector("#selectContact");
        const close_btn = document.querySelector(".close");
        const close_content = document.querySelectorAll(".close-content_delete");
        
        <?php if($user['permission']){ ?>
        const contactForm = document.querySelector("#contact-form");
        const formCtrl = contactForm.querySelector('input[name="ctrl"]');
        const formSet = contactForm.querySelector('input[name="set_id"]');
        const deleteBtns = document.querySelectorAll(".delete_contact");
        const isDeleteBtns = document.querySelectorAll(".is_delete_contact");
        const updateBtns = document.querySelectorAll(".update_contact");

        isDeleteBtns.forEach(isDelete => {
            isDelete.addEventListener("mousedown", ()=>{
               body.querySelector("#detele_content-"+isDelete.getAttribute("data-target")).style.display = "flex";
            });
        });

        close_content.forEach(close => {
            close.addEventListener("mousedown", ()=> {
                close.closest(".profile-modal").style.display = "none";
            });
        });

        deleteBtns.forEach(btn => {
            btn.addEventListener("mousedown", ()=>{
                formCtrl.setAttribute("value", "Contacts.delete");
                formSet.setAttribute("value", btn.getAttribute("data-target"));
                contactForm.submit();
            });
        });

        updateBtns.forEach(btn => {
            btn.addEventListener("mousedown", ()=>{
                formCtrl.setAttribute("value", "Contacts.get");
                formSet.setAttribute("value", btn.getAttribute("data-target"));
                contactForm.submit();
            });
        });

        close_btn && close_btn.addEventListener("mousedown", ()=>{
            close_btn.closest(".profile-modal").remove();
        });
        <?php } ?>

        <?php if($count){ ?>
        selectContact && selectContact.addEventListener("input", ()=>{
            contacts.forEach(contact => {
                const text = contact.innerText.toUpperCase();
                console.log(text);
                if(text.includes(selectContact.value.toUpperCase())){
                    contact.style.display = "flex";
                }else{
                    contact.style.display = "none";
                }
            });
        });

        selectSort.addEventListener("change", ()=>{
            formSort.querySelector("[name='sort_value']").value = selectSort.value;
            formSort.submit();
        });
        <?php } ?>
    </script>
</body>
</html>