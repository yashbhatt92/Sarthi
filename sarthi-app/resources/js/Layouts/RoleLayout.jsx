import { Link, usePage } from '@inertiajs/react';

export default function RoleLayout({ title, children }) {
  const { auth } = usePage().props;

  return (
    <div className="min-h-screen bg-slate-100">
      <header className="bg-slate-900 px-6 py-4 text-white">
        <div className="mx-auto flex max-w-6xl items-center justify-between">
          <div>
            <h1 className="text-xl font-semibold">{title}</h1>
            <p className="text-sm text-slate-300">Role: {auth?.user?.role ?? 'guest'}</p>
          </div>
          <div className="flex gap-3 text-sm">
            <Link href="/dashboard" className="hover:underline">Dashboard</Link>
            <Link href="/requests" className="hover:underline">Requests</Link>
            <Link href="/support" className="hover:underline">Support</Link>
          </div>
        </div>
      </header>
      <main className="mx-auto max-w-6xl p-6">{children}</main>
    </div>
  );
}
