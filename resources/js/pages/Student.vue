<template>
    <div>
        <h2>学生列表</h2>
        <button @click="fetchStudents">加载学生</button>
        <ul>
            <li v-for="student in students" :key="student.id">
                {{ student.name }} - {{ student.email }}
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const students = ref([]);

const fetchStudents = async () => {
    const response = await axios.get("/api/students");
    students.value = response.data;
};
</script>
