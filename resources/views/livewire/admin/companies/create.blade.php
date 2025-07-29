<div>
    <div class="relative w-full mb-6">
        <flux:heading size="xl" level="1">Companies</flux:heading>
        <flux:subheading size="lg" class="mb-6">Create a new company</flux:subheading>
        <flux:separator />
    </div>

    <form wire:submit="save" class="w-full my-6 space-y-6">
        <flux:input label="Company Name" wire:model.live="company.name" :invalid="$errors->has('company.name')" type="text" />
        <flux:input label="Company Email Address" wire:model.live="company.email" :invalid="$errors->has('company.email')" type="email" />
        <flux:input label="Company Website" wire:model.live="company.website" :invalid="$errors->has('company.website')" type="url" />
        <flux:input label="Company logo" wire:model.live="logo" :invalid="$errors->has('logo')" type="file" />

        <flux:button varian="primary" type="submit">Save</flux:button>
    </form>
</div>
