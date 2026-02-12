import { useForm } from '@inertiajs/react';
import CustomerLayout from '@/Layouts/CustomerLayout';

export default function Show({ request }) {
  const { data, setData, post, processing, errors } = useForm({ body: '' });

  const send = (e) => {
    e.preventDefault();
    post(`/requests/${request.id}/chat`);
  };

  return (
    <CustomerLayout>
      <section className="grid gap-4 lg:grid-cols-2">
        <div className="rounded bg-white p-4 shadow">
          <h2 className="text-lg font-semibold">{request.title}</h2>
          <p className="text-sm text-slate-500">Status: {request.status}</p>
          <p className="mt-2 text-sm">{request.description}</p>
        </div>
        <div className="rounded bg-white p-4 shadow">
          <h3 className="mb-2 font-semibold">Real-time Chat</h3>
          <div className="mb-3 max-h-64 space-y-2 overflow-auto rounded border p-2">
            {(request.messages ?? []).map((m) => <p key={m.id} className="text-sm">{m.body}</p>)}
          </div>
          <form onSubmit={send} className="space-y-2">
            <textarea value={data.body} onChange={(e) => setData('body', e.target.value)} className="w-full rounded border p-2" />
            {errors.body && <p className="text-sm text-red-500">{errors.body}</p>}
            <button disabled={processing} className="rounded bg-slate-900 px-3 py-2 text-white">Send</button>
          </form>
        </div>
      </section>
    </CustomerLayout>
  );
}
