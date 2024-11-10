<template>
    <form id="contact-form">
        <div class="form-row">
          <p>
            <label for="nom">Votre nom</label>
            <input type="text" name="nom" id="nom" v-model="nom" required />
          </p>
          <p>
            <label for="prenom">Votre prénom</label>
            <input type="text" name="prenom" id="prenom" v-model="prenom" required />
          </p>
        </div>
        <div class="form-row">
          <p>
            <label for="tel">Votre téléphone (optionnel)</label>
            <input type="tel" name="tel" id="tel" v-model="tel" />
          </p>
          <p>
            <label for="email">Votre E-mail</label>
            <input type="email" name="email" id="email" v-model="email" required />
          </p>
        </div>
        <p>
          <label for="objet">Objet du message</label>
          <input type="text" name="objet" id="objet" v-model="objet" required />
        </p>
        <p>
          <label for="message">Votre message</label>
          <textarea id="message" name="message" v-model="message" class="wd-100"></textarea>
        </p>
        <p>
          <input type="checkbox" name="rgpd" id="rgpd">
          <label for="rgpd" class="d-inline rgpd-text">En validant ce formulaire, j'accepte de transmettre mes données à des fins de relation client, le formulaire est protégé par Google Recaptcha</label> 
        </p>
        <p id="mail-response"></p>
        <p>
          <button @click="sendMail" class="btn-primary">Envoyer</button>
        </p>
      </form>
</template>

<script>
    export default {
        el: "#contact-form",
        data() {
            return {
                nom: '',
                prenom: '',
                tel: '',
                email: '',
                objet: '',
                message: '',
                jsonData: null 
            }
        },
        methods: {
            sendMail(event){
                event.preventDefault();
                var response = document.getElementById('mail-response');
                // Conversion des données en JSON
                const formData = {
                    nom: this.nom,
                    prenom: this.prenom,
                    tel: this.tel,
                    email: this.email,
                    objet: this.objet,
                    message: this.message
                }
                console.log(formData);
                if (this.nom && this.prenom && this.email && this.objet && this.message) {
                    const requestOptions = {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    };

                    fetch('https://lasallecrossfit.fr/api/contact-form', requestOptions)
                      .then(fetchResponse => {
                          if (!fetchResponse.ok) {
                              throw new Error(`La requête a échoué avec le code : ${fetchResponse.status}`);
                          }
                          return fetchResponse.clone().json(); // Clonez la réponse avant de l'analyser
                      })
                      .then(data => {
                          console.log(data);
                          response.classList.remove('response-danger');
                          response.classList.add('response-success');
                          response.innerHTML = "Votre message a bien été envoyé";
                      })
                      .catch(error => {
                          console.error(error);
                          response.classList.remove('response-success');
                          response.classList.add('response-danger');
                          response.innerHTML = "Votre message n'a pas été envoyé ! Une erreur s'est produite !";
                      });
                } else {
                    console.error("Tous les champs obligatoires ne sont pas remplis !");
                }
            }
        },
    }
</script>