// composables/useFormFields.js
export function useFormFields() {
    const fields = [
        {
            id: "name",
            label: "Full Name",
            type: "text",
            placeholder: "Enter your full name",
            required: true,
            icon: `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        `
        },
        {
            id: "email",
            label: "Email Address",
            type: "email",
            placeholder: "Enter your email",
            required: true,
            icon: `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
        `
        },
        {
            id: "password",
            label: "Password",
            type: "password",
            placeholder: "Create a strong password",
            required: true,
            minlength: 8,
            icon: `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        `
        },
        {
            id: "password_confirmation",
            label: "Confirm Password",
            type: "password",
            placeholder: "Confirm your password",
            required: true,
            icon: `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        `
        }
    ]

    return { fields }
}