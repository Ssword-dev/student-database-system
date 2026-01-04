<?php /** @var string|null $error */ ?>
<?php /** @var array $formFields */ ?>
<?php
$formFields = [
    ['label' => 'First Name', 'name' => 'firstName', 'type' => 'text', 'placeholder' => 'Enter your first name', 'required' => 'required'],
    ['label' => 'Last Name', 'name' => 'lastName', 'type' => 'text', 'placeholder' => 'Enter your last name', 'required' => 'required'],
    ['label' => 'Contact Number', 'name' => 'contactNumber', 'type' => 'tel', 'placeholder' => 'Enter your Contact Number', 'required' => 'required'],
    ['label' => 'Address', 'name' => 'address', 'type' => 'text', 'placeholder' => 'Enter your Address', 'required' => 'required'],
    ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'placeholder' => 'Enter your email', 'required' => 'required'],
    ['label' => 'Password', 'name' => 'password', 'type' => 'password', 'placeholder' => 'Enter your password', 'required' => 'required'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="../static/css/shared.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-background">
    <form
        action="/api/auth/signup"
        id="signup-form"
        method="POST"
        class="
            flex flex-col w-full max-w-md p-6 bg-background/110
            outline-text outline-1 outline-solid
            rounded-xl shadow-md space-y-4
        "
    >
        <h1 class="text-2xl font-semibold text-center">Sign Up</h1>

        <?php if (!empty($error)): ?>
            <div class="p-3 bg-red-500/20 border border-red-500 text-red-700 rounded-md text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php foreach ($formFields as $field): ?>
            <div class="flex flex-col relative">
                <label for="<?= htmlspecialchars($field['name']) ?>" class="mb-1 font-medium text-muted-foreground">
                    <?= htmlspecialchars($field['label']) ?>
                </label>
                <input
                    id="<?= htmlspecialchars($field['name']) ?>"
                    name="<?= htmlspecialchars($field['name']) ?>"
                    class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-accent"
                    <?php foreach ($field as $key => $value):
                        if (in_array($key, ['label', 'name'])) continue;
                        echo htmlspecialchars("$key=\"$value\" ");
                    endforeach; ?>
                >
            </div>
        <?php endforeach; ?>

        <button type="submit"
            class="w-full py-2 bg-primary text-text font-semibold rounded-md hover:bg-accent transition">
            Submit
        </button>

        <p class="text-center text-muted-foreground text-sm">
            Already have an account? <a href="./login" class="text-primary hover:underline">Log in</a>
        </p>

        <template id="form-error-template">
            <div class=" p-3 bg-red-500/20 border border-red-500 text-red-700 rounded-md text-sm" id="form-error">
                <span id="form-error-message"></span>
            </div>
        </template>
    </form>

    <script>
        (function(){
            const form = document.getElementById('signup-form');
            /** @type {HTMLTemplateElement} */
            const formErrorTemplate = document.getElementById('form-error-template');
            const endpoint = form.action;
            
            function renderFormError(msg){
                let errorContainer = document.getElementById('form-error');

                if (!errorContainer) {
                    errorContainer = formErrorTemplate.content.cloneNode(true);
                    form.append(errorContainer);
                }

                document.getElementById('form-error-message').innerText = msg;
            }

            document.addEventListener('DOMContentLoaded', () => {

                form.addEventListener('submit', async    (e) => {
                    e.preventDefault(); // prevent normal form submit
                
                    // gather form data
                    const formData = {};
                    new FormData(form).forEach((value, key) => {
                        formData[key] = value;
                    });
                
                    // optional: disable submit button while submitting
                    const submitBtn = form.querySelector('button[type="submit"]');
                    submitBtn.disabled = true;
                
                    // remove previous error messages
                    const prevError = form.querySelector('.form-error');
                    if (prevError) prevError.remove();
                
                    try {
                        const res = await fetch(form.action || window.location.pathname, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(formData)
                        });
                    
                        const data = await res.json();
                    
                        if (res.ok) {
                            // handle success, e.g., redirect
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                alert('Signup successful!');
                            }
                        } else {
                            // handle server validation errors
                            const errMsg = data.error || 'Something went wrong';
                            renderFormError(errMsg);
                        }
                    } catch (err) {
                        renderFormError('Network Error!');
                    } finally {
                        submitBtn.disabled = false;
                    }
                });
            });
        })()
    </script>
</body>
</html>
