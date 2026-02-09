import tailwindcss from "@tailwindcss/vite";
import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";

export default defineConfig({
	plugins: [
		laravel({
			input: [
				"resources/scss/app.scss",
				"resources/scss/dashboard/_index.scss",
			],
			refresh: true,
		}),
		tailwindcss(),
	],
});
