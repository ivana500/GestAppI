<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Option de Paiement</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .payment-option {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
        }
        .payment-option label {
            margin: 10px 0;
        }
        .payment-option input[type="radio"] {
            margin-right: 10px;
        }
        .admin-id-container {
            display: none;
            margin-top: 20px;
            margin-left: 20px;
        }
        .alert {
            margin-top: 10px;
        }
        .img-container {
            margin-right: 30px;
        }
        body {
            background-color: #e0f7fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="img-container">
            <img src="images/argent.png" alt="Image" width="500px">
        </div>
        <div class="payment-option">
            <h3>OPTION DE PAIEMENT</h3>
            <form method="POST" action="" id="paymentForm">
                <label>
                    <input type="radio" name="payment_option" value="orangemoney" onclick="showOrangeForm()"> Orange Money
                </label>
                <label>
                    <input type="radio" name="payment_option" value="mtnmoney" onclick="showMtnForm()"> MTN Money
                </label>
                <label>
                    <input type="radio" name="payment_option" value="cash" onclick="showAdminIdField()"> Paiement au comptant
                </label>

                <!-- Champ ID Administrateur -->
                <div class="admin-id-container" id="admin_id_container">
                    <label for="admin_id">ID Administrateur:</label>
                    <input type="text" name="admin_id" id="admin_id" class="form-control" required>
                    <button type="button" class="btn btn-primary mt-3" id="valider_btn" onclick="validateAdminId()">Valider</button>
                </div>

                <!-- Formulaire Orange Money -->
                <div class="form-group" id="orange_form" style="display:none;">
                    <label for="account_number">Numéro Orange:</label>
                    <input type="text" name="account_number" class="form-control" id="account_number" required>
                    <button type="submit" class="btn btn-success mt-3" id="payer_btn">Payer</button>
                </div>

                <!-- Formulaire MTN Money -->
                <div class="form-group" id="mtn_form" style="display:none;">
                    <label for="account_number_mtn">Numéro MTN:</label>
                    <input type="text" name="account_number" class="form-control" id="account_number_mtn" required>
                    <button type="submit" class="btn btn-success mt-3" id="payer_btn">Payer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Afficher le champ pour l'ID Administrateur
        function showAdminIdField() {
            document.getElementById('admin_id_container').style.display = 'block';
            document.getElementById('orange_form').style.display = 'none';
            document.getElementById('mtn_form').style.display = 'none';
            document.getElementById('payer_btn').style.display = 'none';
            document.getElementById('valider_btn').style.display = 'block';
        }

        // Afficher le formulaire pour Orange Money
        function showOrangeForm() {
            document.getElementById('orange_form').style.display = 'block';
            document.getElementById('mtn_form').style.display = 'none';
            document.getElementById('admin_id_container').style.display = 'none';
            document.getElementById('payer_btn').style.display = 'block';
            document.getElementById('valider_btn').style.display = 'none';
        }

        // Afficher le formulaire pour MTN Money
        function showMtnForm() {
            document.getElementById('mtn_form').style.display = 'block';
            document.getElementById('orange_form').style.display = 'none';
            document.getElementById('admin_id_container').style.display = 'none';
            document.getElementById('payer_btn').style.display = 'block';
            document.getElementById('valider_btn').style.display = 'none';
        }

        // Fonction pour valider l'ID administrateur
        function validateAdminId() {
            var adminId = document.getElementById('admin_id').value;
            var valid = false;
            
          
            
            if (adminId == 'correctID') {
                valid = true;
            }

            if (valid) {
                
                alert('ID Administrateur validé!');
               
                document.getElementById('paymentForm').submit(); 
            } else {
                alert('ID Administrateur invalide!');
            }
        }
    </script>
</body>
</html>
