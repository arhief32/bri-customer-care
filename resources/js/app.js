
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('chat-form', {
    props: ['user', 'privilege_type'],
    data() {
        return {
            new_message: ''
        }
    },
    methods: {
        sendMessage() {
            this.privilege_type == 1 ? this.privilege_type = 'admin' : this.privilege_type = 'user'

            console.log(this.privilege_type, this.user, this.new_message)
            
            axios.post('/chat-application/messages', {
                privilege_type: this.privilege_type,
                privilege_id: this.privilege_id,
                new_message: this.new_message,
            })
            .then(function (response) {
                console.log(response.data)
            })
            
            this.new_message = ''
        },
    },
    template: '<div class="input-group">'+
    '<input type="text"'+ 
    'class="form-control"'+ 
    'placeholder="Input chat here..."'+
    'v-model="new_message" @keyup.enter="sendMessage"'+ 
    '>'+
    '<button class="btn btn-primary" @click="sendMessage"'+ 
    '>Kirim</button>'+
    '</div>'
})

const app = new Vue({
    el: '#application'
});

