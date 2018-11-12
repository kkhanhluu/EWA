document.addEventListener('DOMContentLoaded', function () {
    registerEvents();
});

function registerEvents() {
    "use strict";
    // register events for images
    let pizzaImages = document.getElementsByClassName('pizzaImage');
    for (let i = 0; i < pizzaImages.length; i++) {
        pizzaImages[i].addEventListener('click', eventListenerForPizzaImage, false);
    }

    // register events for button pizzaDelete
    document.getElementById('pizzaDelete').addEventListener('click', eventListenerForButtonPizzaDelete, false);

    // register events for selected pizza 
    let selectedPizza = document.getElementsByClassName('selected');
    for (let i = 0; i < selectedPizza.length; i++) {
        selectedPizza[i].addEventListener('click', eventListenerForSelected, false);
    }

    // register event for button emptyCart
    document.getElementById('emptyCart').addEventListener('click', eventListenerForButtonEmptyCart, false);

    // register event for deleteInput 
    document.getElementById('input-delete').addEventListener('click', eventListenerForEmptyInput, false);

    // register event for input elements 
    document.getElementById('customer').addEventListener('keyup', eventListenerForFormCustomer, false);
}

function eventListenerForPizzaInCart(event) {
    "use strict";
    event.target.classList.add('selected');
}

function eventListenerForPizzaImage(event) {
    "use strict";
    const currentPrice = parseFloat(document.getElementById('total-price').innerHTML);

    // new element to be appended
    let pizzaName = event.target.getAttribute('data-pizza');
    const price = parseFloat(event.target.getAttribute('data-price'));
    let className = "";
    switch (pizzaName) {
        case 'Pizza Margherita':
            className = 'pizza-margherita';
            break;
        case 'Pizza Salami':
            className = 'pizza-salami';
            break;
        case 'Pizza Hawaii':
            className = 'pizza-hawaii';
            break;
        case 'Pizza Casanova':
            className = 'pizza-casanova';
            break;
        default: 
            className = ''; 
            break; 
    }

    // update cart
    let $shoppingCart = document.getElementById('cart');
    // if shopping cart does not have this type of pizza
    if (document.getElementsByClassName(className).length === 0) {
        let newPizza = document.createElement('P');
        newPizza.classList.add('pizza-in-cart', className);
        newPizza.setAttribute('data-quantity', 1);
        newPizza.setAttribute('data-name', pizzaName);
        let newText = document.createTextNode(pizzaName);
        newPizza.appendChild(newText);
        newPizza.dataset.price = price;
        //add event listener for this added pizza
        newPizza.addEventListener('click', eventListenerForPizzaInCart, false);
        $shoppingCart.appendChild(newPizza);
    }
    // else, shopping cart has already had this type of pizza
    else {
        let selectedPizza = document.getElementsByClassName(className)[0]; 
        let quantity = parseInt(selectedPizza.getAttribute('data-quantity')) + 1; 
        selectedPizza.innerHTML = quantity + 'x ' + pizzaName; 
        selectedPizza.setAttribute('data-quantity', quantity); 
    }

    // update price
    document.getElementById('total-price').innerHTML = currentPrice + price;
}

function eventListenerForButtonPizzaDelete(event) {
    let price = parseFloat(document.getElementById('total-price').innerHTML);
    let deletedPizza = document.getElementsByClassName('selected');
    
    while (deletedPizza.length > 0) {
        price -= parseFloat(deletedPizza[0].getAttribute('data-price'));
        if (deletedPizza[0].getAttribute('data-quantity') === '1') {
            deletedPizza[0].parentNode.removeChild(deletedPizza[0]);
        }
        else {
            let quantity = parseInt(deletedPizza[0].getAttribute('data-quantity')) - 1; 
            deletedPizza[0].innerHTML = quantity + 'x ' + deletedPizza[0].getAttribute('data-name'); 
            deletedPizza[0].setAttribute('data-quantity', quantity); 
            deletedPizza[0].classList.remove('selected');
        }
    }
    document.getElementById('total-price').innerHTML = price;
}

function eventListenerForSelected(event) {
    "use strict";
    event.target.classList.remove('selected');
}

function eventListenerForButtonEmptyCart(event) {
    "use strict";
    let deletedPizza = document.getElementsByClassName('pizza-in-cart');
    while (deletedPizza.length > 0) {
        deletedPizza[0].parentNode.removeChild(deletedPizza[0]);
    }

    document.getElementById('total-price').innerHTML = 0;
}

function eventListenerForEmptyInput(event) {
    "use strict";
    event.preventDefault();
    var input = document.querySelectorAll('.form-input').forEach(function (elem) {
        elem.value = '';
    });
}

function eventListenerForFormCustomer() {
    "use strict";
    const inputs = document.getElementsByClassName('form-input');
    let canSubmit = true;

    [].forEach.call(inputs, function (ele) {
        if (ele.required && ele.value.length <= 0) {
            canSubmit = false;
        }
    });

    document.getElementById('send').disabled = !canSubmit;

}