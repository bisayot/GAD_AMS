import { createApp } from 'vue'
import '@fontsource/manrope/400.css'
import '@fontsource/manrope/500.css'
import '@fontsource/manrope/700.css'
import '@fontsource/manrope/800.css'
import 'material-symbols/outlined.css'
import './style.css'
import './assets/report-styles.css'
import App from './App.vue'
import router from './router'
import { VueDatePicker } from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const app = createApp(App)
app.use(router)
app.component('VueDatePicker', VueDatePicker);
app.mount('#app')
