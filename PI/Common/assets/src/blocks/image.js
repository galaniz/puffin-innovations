/**
 * Image block
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
  CheckboxControl,
  RangeControl
} = window.wp.components

const {
  InspectorControls,
  InnerBlocks,
  URLInputButton
} = window.wp.blockEditor

const { withSelect } = window.wp.data
const { Fragment } = window.wp.element
const { registerBlockType } = window.wp.blocks

/* Namespace */

const n = getNamespace(true)
const name = n + 'image'

/* Attributes from serverside */

const nO = getNamespaceObj(getNamespace())
const attr = nO.blocks[name].attr
const def = nO.blocks[name].default

/* Allowed blocks */

const allowedBlocks = ['core/image']

/* Limit innerblocks */

const dataSelector = withSelect((select, ownProps) => {
  const blocks = select('core/block-editor').getBlocks(ownProps.clientId)
  const args = { id: 0 }

  blocks.forEach(b => {
    if (b.name === 'core/image') {
      const { id = 0 } = b.attributes
      args.id = id
    }
  })

  if (args.id) { ownProps.attributes.id = args.id }

  args.isMaxBlock = blocks.length === 1

  return args
})

/* Block */

registerBlockType(name, {
  title: 'Figure',
  category: 'theme-blocks',
  icon: 'camera-alt',
  attributes: attr,
  edit: dataSelector(props => {
    const { attributes, setAttributes, isMaxBlock } = props

    const {
      width_mobile = def.width_mobile, // eslint-disable-line camelcase
      width = def.width,
      height = def.height,
      aspect_ratio = def.aspect_ratio, // eslint-disable-line camelcase
      border_radius = def.border_radius, // eslint-disable-line camelcase
      cover = def.cover,
      order_first = def.order_first, // eslint-disable-line camelcase
      is_link = def.is_link, // eslint-disable-line camelcase
      link = def.link,
      opacity = def.opacity,
      position = def.position
    } = attributes

    /* Width options */

    const widthOptions = [
      { label: 'Auto', value: '' },
      { label: '40px', value: 's' },
      { label: '60px', value: 'm' },
      { label: '80px', value: 'l' },
      { label: '100px', value: 'xl' },
      { label: '200px', value: '4xl' },
      { label: '100%', value: '1-1' }
    ]

    /* Height options */

    const heightOptions = [
      { label: 'Auto', value: '' },
      { label: '40px', value: 's' },
      { label: '60px', value: 'm' },
      { label: '80px', value: 'l' },
      { label: '100px', value: 'xl' },
      { label: '200px', value: '4xl' },
      { label: '100%', value: '1-1' }
    ]

    /* Position options */

    const positionOptions = [
      { label: 'Left Top', value: 'left-top' },
      { label: 'Left Center', value: 'left-center' },
      { label: 'Left Bottom', value: 'left-bottom' },
      { label: 'Center Top', value: 'center-top' },
      { label: 'Center Center', value: 'center-center' },
      { label: 'Center Bottom', value: 'center-bottom' },
      { label: 'Right Top', value: 'right-top' },
      { label: 'Right Center', value: 'right-center' },
      { label: 'Right Bottom', value: 'right-bottom' }
    ]

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Image Options'>
            <SelectControl
              label='Width'
              value={width_mobile} // eslint-disable-line camelcase
              options={widthOptions}
              onChange={v => setAttributes({ width_mobile: v })}
            />
            <SelectControl
              label='Width Larger Screens'
              value={width}
              options={widthOptions}
              onChange={width => setAttributes({ width })}
            />
            <SelectControl
              label='Height'
              value={height}
              options={heightOptions}
              onChange={height => setAttributes({ height })}
            />
            <SelectControl
              label='Aspect Ratio'
              value={aspect_ratio} // eslint-disable-line camelcase
              options={[
                { label: 'None', value: '' },
                { label: '1:1', value: '100' },
                { label: '3:2', value: '66' },
                { label: '16:9', value: '56' }
              ]}
              onChange={v => setAttributes({ aspect_ratio: v })}
            />
            <SelectControl
              label='Border Radius'
              value={border_radius} // eslint-disable-line camelcase
              options={[
                { label: 'None', value: '' },
                { label: '100%', value: '100-pc' },
                { label: '15px', value: 'm' },
                { label: '30px', value: 'xl' }
              ]}
              onChange={v => setAttributes({ border_radius: v })}
            />
            <SelectControl
              label='Position'
              value={position} // eslint-disable-line camelcase
              options={positionOptions}
              onChange={position => setAttributes({ position })}
            />
            <CheckboxControl
              label='Cover'
              help='Fill specified width and/or height'
              value='1'
              checked={!!cover} // eslint-disable-line camelcase
              onChange={cover => setAttributes({ cover })}
            />
            <CheckboxControl
              label='Order First'
              help='Visually appear as first item'
              value='1'
              checked={!!order_first} // eslint-disable-line camelcase
              onChange={v => setAttributes({ order_first: v })}
            />
            <CheckboxControl
              label='Link'
              value='1'
              checked={!!is_link} // eslint-disable-line camelcase
              onChange={v => setAttributes({ is_link: v })}
            />
            <RangeControl
              label='Opacity'
              value={opacity}
              onChange={opacity => setAttributes({ opacity })}
              min={0}
              max={100}
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title='Figure' initialOpen={false}>
          <InnerBlocks
            templateLock={false}
            allowedBlocks={isMaxBlock ? [] : allowedBlocks}
          />
          {is_link && ( // eslint-disable-line camelcase
            <BaseControl label='Link'>
              <URLInputButton
                url={link}
                onChange={link => setAttributes({ link })}
              />
            </BaseControl>
          )}
        </PanelBody>
      </Panel>
    ]
  }),
  save () {
    return <InnerBlocks.Content /> // Rendered in php
  }
})
