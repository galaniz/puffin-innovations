/**
 * Collapsible block
 */

/* Dependencies */

const {
  getNamespace,
  getNamespaceObj
} = window.blockUtils

const {
  Panel,
  PanelBody,
  BaseControl,
  SelectControl,
  CheckboxControl
} = window.wp.components

const {
  InspectorControls,
  InnerBlocks,
  PlainText
} = window.wp.blockEditor

const { Fragment } = window.wp.element
const { registerBlockType } = window.wp.blocks

/* Namespace */

const n = getNamespace(true)
const name = n + 'collapsible'

/* Attributes from serverside */

const nO = getNamespaceObj(getNamespace())
const attr = nO.blocks[name].attr
const def = nO.blocks[name].default

/* Block */

registerBlockType(name, {
  title: 'Collapsible',
  category: 'theme-blocks',
  icon: 'plus',
  attributes: attr,
  parent: [n + 'collapsibles'],
  edit (props) {
    const { attributes, setAttributes } = props

    const {
      title = def.title,
      heading_level = def.heading_level, // eslint-disable-line camelcase
      open = def.open
    } = attributes

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Collapsible Options'>
            <SelectControl
              label='Heading Level'
              value={heading_level} // eslint-disable-line camelcase
              options={[
                { label: 'Heading Two', value: 'h2' },
                { label: 'Heading Three', value: 'h3' },
                { label: 'Heading Four', value: 'h4' },
                { label: 'Heading Five', value: 'h5' }
              ]}
              onChange={v => setAttributes({ heading_level: v })}
            />
            <CheckboxControl
              label='Open'
              help='Start open'
              value='1'
              checked={!!open}
              onChange={open => setAttributes({ open })}
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title={`Collapsible${title ? `: ${title}` : ''}`} initialOpen={false}>
          <PlainText
            className='t-h4'
            value={title} // eslint-disable-line camelcase
            onChange={title => setAttributes({ title })}
            placeholder='Title'
          />
          <div style={{ paddingTop: '1.25rem' }}>
            <BaseControl label='Content'>
              <div className='t t-inherit'>
                <InnerBlocks allowedBlocks={['core/paragraph', 'core/heading', 'core/list', n + 'text']} />
              </div>
            </BaseControl>
          </div>
        </PanelBody>
      </Panel>
    ]
  },
  save () {
    return <InnerBlocks.Content /> // Rendered in php
  }
})
