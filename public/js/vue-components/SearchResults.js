new Vue({
    el: '#search-results',
    delimiters: ['${', '}'],
    data: {
        query: '',
        results: [],
        page: 1
    },
    methods: {
        search() {
            // Reset page to 1 when performing a new search
            this.page = 1;

            if (this.query.length >= 2) {
                this.fetchResults(this.query);
            } else {
                // Clear results if there are fewer than two characters
                this.results = [];
            }
        },
        fetchResults(keywords, append = false) {
            axios.get(`/search?q=${this.query}&page=${this.page}`)
                .then(response => {
                    // Append new results or replace existing results based on 'append' flag
                    this.results = append ? [...this.results, ...response.data] : response.data;
                })
                .catch(error => {
                    console.error('Error fetching results:', error);
                });
        },
        loadMore() {
            // Increment the page number before fetching more results
            this.page++;

            // Fetch additional results from Symfony controller endpoint
            this.fetchResults(this.query, true);
        },
    },
    created() {
        // Initial search on component creation
        // this.search();
    },
});