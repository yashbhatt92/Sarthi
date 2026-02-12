import { Link } from '@inertiajs/react';
import CustomerLayout from '@/Layouts/CustomerLayout';

export default function Index({ requests }) {
  const rows = requests?.data ?? [];

  return (
    <CustomerLayout>
      <section className="rounded bg-white p-4 shadow">
        <h2 className="mb-3 text-lg font-semibold">Service Requests</h2>
        {rows.length === 0 ? (
          <p className="text-sm text-slate-500">No requests found.</p>
        ) : (
          <ul className="space-y-2">
            {rows.map((row) => (
              <li key={row.id} className="rounded border p-3">
                <div className="flex items-center justify-between">
                  <div>
                    <p className="font-medium">{row.title}</p>
                    <p className="text-xs text-slate-500">Status: {row.status}</p>
                  </div>
                  <Link className="text-blue-600" href={`/requests/${row.id}`}>Open</Link>
                </div>
              </li>
            ))}
          </ul>
        )}
      </section>
    </CustomerLayout>
  );
}
