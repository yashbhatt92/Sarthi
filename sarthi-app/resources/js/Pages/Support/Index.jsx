import { useForm } from '@inertiajs/react';
import CustomerLayout from '@/Layouts/CustomerLayout';

export default function Index({ tickets }) {
  const { data, setData, post, processing } = useForm({ subject: '', message: '' });

  return (
    <CustomerLayout>
      <div className="grid gap-4 lg:grid-cols-2">
        <section className="rounded bg-white p-4 shadow">
          <h2 className="mb-2 font-semibold">Create Support Ticket</h2>
          <form onSubmit={(e) => { e.preventDefault(); post('/support'); }} className="space-y-2">
            <input className="w-full rounded border p-2" placeholder="Subject" value={data.subject} onChange={(e) => setData('subject', e.target.value)} />
            <textarea className="w-full rounded border p-2" placeholder="Message" value={data.message} onChange={(e) => setData('message', e.target.value)} />
            <button disabled={processing} className="rounded bg-slate-900 px-3 py-2 text-white">Submit</button>
          </form>
        </section>
        <section className="rounded bg-white p-4 shadow">
          <h2 className="mb-2 font-semibold">Tickets</h2>
          {(tickets?.data ?? []).length === 0 ? <p className="text-sm text-slate-500">No support conversations yet.</p> : (
            <ul className="space-y-2">{tickets.data.map((ticket) => <li key={ticket.id} className="rounded border p-2 text-sm">{ticket.subject}</li>)}</ul>
          )}
        </section>
      </div>
    </CustomerLayout>
  );
}
