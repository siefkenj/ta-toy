// export as global since we're not using a module system
window.PageNotFound = Vue.component("PageNotFound", {
    template: `
        <div class="PageNotFound">
            <h1>404 Error: Page Not Found</h1>
        </div>`
});
