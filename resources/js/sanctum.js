export async function initSanctum() {
    await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
}
