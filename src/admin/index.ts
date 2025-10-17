import Alpine from 'alpinejs';
import sort from '@alpinejs/sort';
import { ModuleFacade } from '../../global';

Alpine.plugin(sort);

window.Alpine = Alpine;

Alpine.start();

if (window.mx?.moduleGroupsEnabled && window.Modularity?.Editor?.Module) {
  const Module = window.Modularity.Editor.Module.constructor;

  const MxModule = function MxModule(
    this: ModuleFacade,
    ...args: ConstructorParameters<typeof ModuleFacade>
  ) {
    Module.apply(this, args);
  } as any as new (
    ...args: ConstructorParameters<typeof ModuleFacade>
  ) => ModuleFacade;

  MxModule.prototype = Object.create(Module.prototype);
  MxModule.prototype.constructor = MxModule;

  MxModule.prototype.isEditingModule = function isEditingModule() {
    return this.editingModule ?? false;
  };

  MxModule.prototype.updateModule = function (moduleEl, data) {
    const xData = Alpine.closestDataStack(moduleEl)[0];
    xData.postId = data.post_id ?? xData.postId;
    xData.postTitle = data.title ?? xData.postTitle;
    xData.editingModule = false;
  } as ModuleFacade['updateModule'];

  window.Modularity.Editor.Module = new MxModule(window.Modularity);
}
