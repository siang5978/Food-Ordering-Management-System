<link href="/Assignment/View/CSS/AddProduct_Admin.css" rel="stylesheet" type="text/css" />
<div class="add-product-container">
    <h2>Add Product</h2>
    <div class="error-message" id="errorMessage" style="display:none;"></div>

    <form id="addProductForm" class="add-product-form">
        <label for="name">Product Name</label>
        <input type="text" id="name" name="name" placeholder="Enter Product Name" required/>

        <label for="description">Description</label>
        <input type="text" id="description" name="description" placeholder="Enter Product Description" required/>

        <label for="stock">Stock</label>
        <input type="number" id="stock" name="stock" placeholder="Enter Stock" required/>
        
        <label for="price">Price</label>
        <input type="number" step="0.01" id="price" name="price" placeholder="Enter Price" required/>
        
        <label for="type">Type</label>
        <select id="type" name="type" required>
            <option value="Food">Food</option>
            <option value="Drink">Drink</option>
        </select>
        
        <div id="volume-container">
            <label for="volume">Volume (ml)</label>
            <input type="number" id="volume" name="volume" placeholder="Enter Volume"/>
        </div>
        
        <button type="submit">Add</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const volumeContainer = document.getElementById('volume-container');
    const volumeInput = document.getElementById('volume');
    const form = document.getElementById('addProductForm');
    const errorMessageDiv = document.getElementById('errorMessage');

    volumeContainer.style.display = 'none';

    typeSelect.addEventListener('change', function() {
        if (this.value === 'Drink') {
            volumeContainer.style.display = 'block';
        } else {
            volumeContainer.style.display = 'none';
            volumeInput.value = '';
        }
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        let name = document.getElementById('name').value.trim();
        let description = document.getElementById('description').value.trim();
        let stock = document.getElementById('stock').value.trim();
        let price = document.getElementById('price').value.trim();
        let type = typeSelect.value;
        let volume = volumeInput.value.trim();

        if (name.length < 3) { alert("Product name must be at least 3 characters."); return; }
        if (description.length < 5) { alert("Description must be at least 5 characters."); return; }
        if (!/^[1-9][0-9]*$/.test(stock)) { alert("Stock must be a whole number greater than 0."); return; }
        if (!/^[0-9]+(\.[0-9]{1,2})?$/.test(price) || parseFloat(price) <= 0) { alert("Price must be a positive number with up to 2 decimal places."); return; }
        if (type === "Drink" && (!/^[1-9][0-9]*$/.test(volume))) { alert("Volume must be a whole number greater than 0 when product type is Drink."); return; }

        const data = {
            name: name,
            description: description,
            stock: parseInt(stock),
            price: parseFloat(price),
            type: type,
            volume: type === 'Drink' ? parseInt(volume) : null
        };

        try {
            const response = await fetch('/Assignment/index.php/api/product', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                alert("Product added successfully!");
                window.location.href = "/Assignment/view/Product_Admin.php";
            } else {
                alert(result.errors ? result.errors.join("\n") : result.error);
            }
        } catch (err) {
            alert("Error: " + err.message);
        }
    });
});
</script>
