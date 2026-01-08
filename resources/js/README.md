# Frontend JavaScript/TypeScript Structure

This directory contains all frontend TypeScript code for the Vue 3 application.

## Directory Structure

### `/components`
Reusable Vue components that can be used across multiple pages.

**Examples:**
- Button components
- Form inputs
- Modal dialogs
- Navigation bars
- Cards

**Naming Convention:** PascalCase (e.g., `UserCard.vue`, `DataTable.vue`)

### `/pages`
Page-level components that represent entire views/routes.

**Examples:**
- Dashboard page
- User profile page
- Order list page
- Order detail page

**Naming Convention:** PascalCase with descriptive names (e.g., `OrderList.vue`, `UserProfile.vue`)

### `/layouts`
Layout wrapper components that define page structure.

**Examples:**
- Main application layout
- Authentication layout
- Empty layout

**Naming Convention:** PascalCase (e.g., `MainLayout.vue`, `AuthLayout.vue`)

### `/types`
TypeScript type definitions and interfaces.

**Examples:**
- API response types
- Model interfaces
- Component prop types
- Utility types

**Naming Convention:** PascalCase for types, camelCase for files (e.g., `user.ts` containing `User`, `UserProfile` types)

## Entry Points

### `app.ts`
Main application entry point. Initializes Vue app and mounts to DOM.

### `bootstrap.ts`
Application bootstrap file. Sets up axios and global configurations.

### `vite-env.d.ts`
TypeScript environment declarations for Vite.

## Best Practices

1. **Use Composition API** with `<script setup lang="ts">` syntax
2. **Type Everything** - Use TypeScript types for props, emits, and refs
3. **Component Organization:**
   ```vue
   <script setup lang="ts">
   // Imports
   // Props/Emits definitions
   // Reactive state
   // Computed properties
   // Methods
   // Lifecycle hooks
   </script>

   <template>
     <!-- Template -->
   </template>

   <style scoped>
   /* Component-specific styles */
   </style>
   ```

4. **Props Definition:**
   ```typescript
   interface Props {
     title: string;
     count?: number;
   }
   
   const props = withDefaults(defineProps<Props>(), {
     count: 0
   });
   ```

5. **Emits Definition:**
   ```typescript
   interface Emits {
     (e: 'update', value: string): void;
     (e: 'close'): void;
   }
   
   const emit = defineEmits<Emits>();
   ```

6. **Refs with Types:**
   ```typescript
   const count = ref<number>(0);
   const user = ref<User | null>(null);
   ```

## Example Component

```vue
<template>
  <div class="user-card">
    <h3>{{ user.name }}</h3>
    <p>{{ user.email }}</p>
    <button @click="handleClick">View Profile</button>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

interface User {
  name: string;
  email: string;
}

interface Props {
  user: User;
}

interface Emits {
  (e: 'view-profile', userId: number): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const handleClick = () => {
  emit('view-profile', 123);
};
</script>

<style scoped>
.user-card {
  padding: 1rem;
  border: 1px solid #ddd;
  border-radius: 8px;
}
</style>
```
