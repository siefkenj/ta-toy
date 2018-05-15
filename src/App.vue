<style>

@import './css/app.css';

</style>

<template>

<div id="app">
    <div id="app">
        <div id="page">
            <navbar>
                <h1>Tutorial Feedback</h1>
            </navbar>
            <div id="container">
                <router-view></router-view>
            </div>
            <div id="footer">
                <p>University of Toronto</p>
            </div>
        </div>
    </div>
</div>

</template>

<script>

export default {
    name: "app",
    data() {
        return {
            msg: "Welcome to Your Vue.js App"
        };
    }
};

</script>
