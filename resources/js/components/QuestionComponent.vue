<template>
    <div class="container font-serif text-lg">
        <ApolloQuery :query="query" :variables="{ id: id}">
            <template slot-scope="{ result: { data, loading }, isLoading }">
            <div v-if="isLoading">Loading...</div>
            <div v-else>
                <div v-if="data" class="mb-10 p-4 border-b-4">
                    <h1><a class="text-grey-dark hover:text-grey-darkest"
                        :href="'https://www.reddit.com/'+data.question.permalink">{{data.question.title}}</a></h1>
                    <vue-markdown>{{data.question.text}}</vue-markdown>
                </div>
                <div v-if="data">
                    <div class="my-4 p-4 border-b"
                        v-for="answer of data.question.answers"
                        :key="answer.id" > 
                        <div v-html="parse(answer.body_html)">
                        </div>
                    </div>
                </div>
            </div>
            </template>
        </ApolloQuery>
    </div>
</template>

<script>
var he = require('he');
import questionQuery from '../graphql/question.gql'

export default {
    data() {
        return {
            query: questionQuery
        }
    },
    props: ['id'],
    methods: {
        parse(message) {
            return he.decode(message)
        }
    }   
}
</script>