
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import ApolloClient from "apollo-boost"
import VueApollo from "vue-apollo"

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('questions-component', require('./components/QuestionsComponent.vue').default);
Vue.component('question-component', require('./components/QuestionComponent.vue').default);
Vue.component('vue-markdown', require('vue-markdown').default);
const apolloProvider = new VueApollo({
    defaultClient: new ApolloClient({
        uri: "/graphql"
    })
});
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(VueApollo);

const app = new Vue({
    el: '#app',
    apolloProvider
});
