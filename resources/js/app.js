
import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // 确保 router 被正确导入

const app = createApp(App);

app.use(router); // 确保 Vue 应用使用了路由
app.mount('#app');
