<?php $__env->startSection('title', 'Kelola Barang'); ?>
<?php $__env->startSection('page_title', 'Master Data Barang'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <p class="text-sm text-slate-400">Daftar inventaris barang dan lokasi ruangan penyimpanan.</p>
        </div>
        <a href="<?php echo e(route('items.create')); ?>" class="px-4 py-2.5 bg-violet-600 hover:bg-violet-500 active:scale-[0.98] transition-all duration-150 text-white text-xs font-semibold rounded-xl flex items-center gap-2 shadow-lg shadow-violet-600/10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Barang Baru
        </a>
    </div>

    <!-- Data Table Card -->
    <div class="bg-slate-950/40 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/60">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Ruangan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 text-center uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-900/30 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-slate-400 font-medium"><?php echo e($index + 1); ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-violet-400">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-200"><?php echo e($item['item_name']); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                <span class="px-2.5 py-0.5 rounded bg-slate-800 border border-slate-700 text-xs font-medium text-slate-400">
                                    <?php echo e($item['category']); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold <?php echo e($item['stock'] > 5 ? 'text-slate-300' : 'text-amber-400'); ?>">
                                    <?php echo e($item['stock']); ?> Unit
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if(isset($item['room'])): ?>
                                    <div class="text-sm text-slate-300 font-semibold"><?php echo e($item['room']['room_name']); ?></div>
                                    <div class="text-xs text-slate-500"><?php echo e($item['room']['location']); ?></div>
                                <?php else: ?>
                                    <span class="text-xs text-rose-400 italic">Tanpa Ruangan</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?php echo e(route('items.edit', $item['id'])); ?>" class="p-2 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 transition-all duration-150" title="Edit Barang">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="<?php echo e(route('items.destroy', $item['id'])); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition-all duration-150" title="Hapus Barang">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500 text-sm">Belum ada data barang.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tubespwluas-main\resources\views/items/index.blade.php ENDPATH**/ ?>