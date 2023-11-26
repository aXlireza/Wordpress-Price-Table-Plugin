// Check if the specific ID exists
if (specificIdElement) {
    const the_rate = 0.09;
    function enable_rate() {
        specificIdElement.querySelector('#pricetable_mainbody_by_factory .checkbox-container input').checked = true;
        const textContainer = specificIdElement.querySelector('#pricetable_mainbody_by_factory .ratebox-container .text-container');
        textContainer.innerText = 'قیمت های درج شده برای یک ظرفیت و با احتساب %۹ ارزش افزوده می بـاشد.';
        textContainer.classList.add('active');
        [...specificIdElement.querySelectorAll('#pricetable_mainbody_by_factory .rate_checked_warning')].forEach(item => item.classList.remove('hidden'));
        [...specificIdElement.querySelectorAll("#pricetable_mainbody_by_factory .price .pricenumber")].forEach(price => {
            const pricenum = Number(price.getAttribute('original_price'));
            const newprice = pricenum + pricenum*the_rate;
            price.innerHTML = en_to_fa_num(newprice.toString())
        })
    }

    function disable_rate() {
        specificIdElement.querySelector('#pricetable_mainbody_by_factory .checkbox-container input').checked = false;
        const textContainer = specificIdElement.querySelector('#pricetable_mainbody_by_factory .ratebox-container .text-container');
        textContainer.innerText = 'قیمت های درج شده برای یک ظرفیت می بـاشد.';
        textContainer.classList.remove('active');
        [...specificIdElement.querySelectorAll('#pricetable_mainbody_by_factory .rate_checked_warning')].forEach(item => item.classList.add('hidden'));
        [...specificIdElement.querySelectorAll("#pricetable_mainbody_by_factory .price .pricenumber")].forEach(price => {
            const pricenum = Number(price.getAttribute('original_price'));
            price.innerHTML = en_to_fa_num(pricenum.toString())
        })
    }

    let checkbox_handler_state = false;
    function checkbox_handler() {
        checkbox_handler_state = !checkbox_handler_state
        if (checkbox_handler_state) enable_rate()
        else disable_rate()
    }

    // enable the rates for the tables renderred by size
    [...specificIdElement.querySelectorAll("#pricetable_mainbody_by_size .price .pricenumber")].forEach(price => {
        const pricenum = Number(price.getAttribute('original_price'));
        const newprice = pricenum + pricenum*the_rate;
        price.innerHTML = en_to_fa_num(newprice.toString())
    })
}