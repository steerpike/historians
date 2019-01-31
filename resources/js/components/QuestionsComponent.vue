<template>
    <div class="container font-serif text-lg">
        <div class="flex flex-wrap">
            <div class="w-1/3">
            <ApolloQuery :query="$options.query">
                <template slot-scope="{result: {loading, data, error }}">
                    <div v-if="loading">Loading...</div>
                    <div v-else>
                        <ul v-if="data">
                            <li v-for="ontology of data.ontologies" :key="ontology.id">
                                <a href="#" 
                                    class="text-grey-dark hover:text-grey-darkest" 
                                    @click="selectCategory(ontology.id)">
                                    {{ontology.label}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </template>
            </ApolloQuery>
            </div>
            <div class="w-2/3">
            <ApolloQuery 
                :query="query"
                :variables="{ page: selectedPage }" 
                v-if="selectedCategory === 'all'">
                <template slot-scope="{result: {loading, data, error }}">
                    <div v-if="loading">Loading...</div>
                    <div v-else>
                        <ul v-if="data">
                            <li v-for="question of data.questions.data">
                                <a
                                class="text-grey-dark hover:text-grey-darkest"  
                                :href="'/questions/'+question.id">{{question.title}}</a>
                            </li>
                        </ul>
                        <ul v-if="data">
                            <li 
                                v-for="number in data.questions.paginatorInfo.lastPage"
                                @click="selectPage(number)">
                                {{number}}
                            </li>
                        </ul>
                    </div>
                </template>
            </ApolloQuery>
            <ApolloQuery :query="query" :variables="{ id: selectedCategory }" v-else>
                <template slot-scope="{ result: { data, loading }, isLoading }">
                <div v-if="isLoading">Loading...</div>
                <div v-else>
                    <ul v-if="data">
                        <li v-for="question of data.ontology.questions" :key="question.id">
                        <a class="text-grey-dark hover:text-grey-darkest" 
                        :href="'/questions/'+question.id">({{question.author}}) {{question.title}}</a></li>
                    </ul>
                </div>
                </template>
            </ApolloQuery>
            </div>
        </div>
    </div>
</template>

<script>
   import gql from 'graphql-tag';
   import questionsQuery from '../graphql/questions.gql'
   import categoryQuery from '../graphql/category.gql'
   import paginateQuery from '../graphql/paginateQuestions.gql'
    export default {
        data() {
            return {
                selectedCategory: 'all',
                selectedPage: 2,
                questionsQuery,
                query: paginateQuery
            }     
        },
        methods: {
            selectCategory(category) {
                if(category === 'all') {
                    this.query = paginateQuery
                } else{
                    this.query = categoryQuery
                }
                this.selectedCategory = category
            },
            selectPage(page) {
                this.selectedPage = page
            }
        },
        query: gql`
            query {
                ontologies(type: "category") {
                    id
                    label
                }
            }
        `
    }
</script>